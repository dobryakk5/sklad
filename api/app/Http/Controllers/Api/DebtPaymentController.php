<?php

namespace App\Http\Controllers\Api;

use App\Models\DebtPaymentLink;
use App\Services\Cabinet\BitrixCabinetException;
use App\Services\DebtPayments\BitrixDebtPaymentClient;
use App\Services\DebtPayments\DebtPaymentPageService;
use App\Services\DebtPayments\BitrixDebtPaymentVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DebtPaymentController extends ApiController
{
    public function __construct(
        private readonly DebtPaymentPageService $pageService,
        private readonly BitrixDebtPaymentClient $client,
        private readonly BitrixDebtPaymentVerifier $verifier,
    ) {}

    public function show(string $token): JsonResponse
    {
        return response()->json($this->pageService->show($token));
    }

    public function pay(string $token): JsonResponse
    {
        $link = DebtPaymentLink::where('token', $token)->first();

        if ($link === null) {
            return response()->json(['error' => ['code' => 'NOT_FOUND']], 404);
        }

        $state = $this->pageService->show($token);
        if (($state['state'] ?? null) === 'payment_created' && $link->payment_url) {
            return response()->json([
                'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
                'payment_url' => $link->payment_url,
            ]);
        }

        if (($state['state'] ?? null) !== 'ready') {
            return response()->json([
                'error' => [
                    'code' => 'LINK_NOT_PAYABLE',
                    'state' => $state['state'] ?? 'error',
                ],
            ], 422);
        }

        $verification = $this->verifier->verifyInvoicePayable($link);
        if (($verification['ok'] ?? false) !== true) {
            return $this->invoiceVerificationFailed($link, $verification);
        }

        try {
            $payment = $this->client->payInvoice((int) $link->bitrix_user_id, (int) $link->invoice_id);
        } catch (BitrixCabinetException $e) {
            $link->update([
                'status' => DebtPaymentLink::STATUS_ERROR,
                'last_error' => $e->getMessage() ?: $e->getCode(),
            ]);

            Log::channel('debt_payments')->warning('Debt payment creation failed.', [
                'link_id' => $link->id,
                'invoice_id' => $link->invoice_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => ['code' => $e->getErrorCode()]], $e->getHttpStatus());
        }

        DB::transaction(function () use ($link, $payment): void {
            $link->update([
                'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
                'bitrix_order_id' => $payment['order_id'],
                'payment_url' => $payment['payment_url'],
                'last_error' => null,
            ]);
        });

        return response()->json([
            'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
            'payment_url' => $payment['payment_url'],
        ]);
    }

    private function invoiceVerificationFailed(DebtPaymentLink $link, array $verification): JsonResponse
    {
        $code = (string) ($verification['code'] ?? 'INVOICE_NOT_PAYABLE');

        if ($code === 'INVOICE_ALREADY_PAID') {
            $link->update([
                'status' => DebtPaymentLink::STATUS_PAID,
                'paid_at' => now(),
                'last_error' => null,
            ]);

            return response()->json(['error' => ['code' => $code]], 422);
        }

        if ($code === 'AMOUNT_MISMATCH') {
            $link->update([
                'status' => DebtPaymentLink::STATUS_ERROR,
                'last_error' => 'amount_mismatch',
            ]);

            Log::channel('debt_payments')->warning('Debt payment invoice amount mismatch before payment.', [
                'link_id' => $link->id,
                'invoice_id' => $link->invoice_id,
                'expected' => $verification['expected'] ?? null,
                'actual' => $verification['actual'] ?? null,
            ]);
        } else {
            $link->update([
                'last_error' => strtolower($code) . (($verification['status_id'] ?? null) !== null ? ':' . $verification['status_id'] : ''),
            ]);
        }

        return response()->json([
            'error' => [
                'code' => $code,
                'status_id' => $verification['status_id'] ?? null,
            ],
        ], $code === 'INVOICE_NOT_FOUND' ? 404 : 422);
    }
}
