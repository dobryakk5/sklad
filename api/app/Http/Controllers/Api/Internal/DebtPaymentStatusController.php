<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Api\ApiController;
use App\Services\DebtPayments\DebtPaymentStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DebtPaymentStatusController extends ApiController
{
    public function __construct(
        private readonly DebtPaymentStatusService $statusService,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $secret = (string) config('services.bitrix.internal_secret');

        if ($secret === '' || ! hash_equals($secret, (string) $request->header('X-Internal-Secret'))) {
            return response()->json(['error' => ['code' => 'FORBIDDEN']], 403);
        }

        $payload = $request->validate([
            'order_id' => ['required', 'integer'],
            'payment_id' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'paid_at' => ['nullable', 'date'],
            'amount' => ['nullable', 'string'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $result = $this->statusService->applyBitrixStatus($payload);

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }
}
