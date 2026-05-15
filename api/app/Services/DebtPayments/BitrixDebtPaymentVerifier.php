<?php

namespace App\Services\DebtPayments;

use App\Models\DebtPaymentLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BitrixDebtPaymentVerifier
{
    private const DB_CONNECTION = 'bitrix';
    private const INVOICES_IBLOCK_ID = 53;
    private const INVOICE_STATUS_NOT_PAID = 354;
    private const INVOICE_STATUS_PAID = 355;

    public function verifyInvoicePayable(DebtPaymentLink $link): array
    {
        $invoice = $this->getInvoice((int) $link->invoice_id);

        if ($invoice === null || (string) ($invoice['active'] ?? '') !== 'Y') {
            return ['ok' => false, 'code' => 'INVOICE_NOT_FOUND'];
        }

        if ((int) ($invoice['user_id'] ?? 0) !== (int) $link->bitrix_user_id) {
            return ['ok' => false, 'code' => 'FORBIDDEN'];
        }

        $statusId = (int) ($invoice['status_id'] ?? 0);
        if ($statusId === self::INVOICE_STATUS_PAID) {
            return ['ok' => false, 'code' => 'INVOICE_ALREADY_PAID', 'status_id' => $statusId];
        }

        if ($statusId !== self::INVOICE_STATUS_NOT_PAID) {
            return ['ok' => false, 'code' => 'INVOICE_NOT_PAYABLE', 'status_id' => $statusId];
        }

        $amount = DecimalString::normalize((string) ($invoice['total_amount'] ?? '0'));
        if (! DecimalString::equals($amount, (string) $link->amount)) {
            return [
                'ok' => false,
                'code' => 'AMOUNT_MISMATCH',
                'expected' => (string) $link->amount,
                'actual' => $amount,
            ];
        }

        return ['ok' => true, 'invoice' => $invoice];
    }

    public function getInvoice(int $invoiceId): ?array
    {
        $invoiceGuidSelect = Schema::connection(self::DB_CONNECTION)
            ->hasColumn('b_iblock_element_prop_s53', 'PROPERTY_527')
            ? 's.PROPERTY_527 as invoice_guid'
            : 'NULL as invoice_guid';

        $row = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as e')
            ->leftJoin('b_iblock_element_prop_s53 as s', 's.IBLOCK_ELEMENT_ID', '=', 'e.ID')
            ->where('e.IBLOCK_ID', self::INVOICES_IBLOCK_ID)
            ->where('e.ID', $invoiceId)
            ->selectRaw('
                e.ID as invoice_id,
                e.ACTIVE as active,
                e.NAME as name,
                s.PROPERTY_518 as invoice_number,
                s.PROPERTY_524 as status_id,
                s.PROPERTY_525 as user_id,
                s.PROPERTY_526 as contract_guid,
                ' . $invoiceGuidSelect . ',
                s.PROPERTY_670 as total_amount
            ')
            ->first();

        if ($row === null) {
            return null;
        }

        return [
            'invoice_id' => (int) $row->invoice_id,
            'active' => (string) $row->active,
            'name' => (string) ($row->name ?? ''),
            'invoice_number' => (string) ($row->invoice_number ?? ''),
            'status_id' => (int) ($row->status_id ?? 0),
            'user_id' => (int) ($row->user_id ?? 0),
            'contract_guid' => (string) ($row->contract_guid ?? ''),
            'invoice_guid' => $row->invoice_guid !== null ? (string) $row->invoice_guid : null,
            'total_amount' => DecimalString::normalize((string) ($row->total_amount ?? '0')),
        ];
    }

    public function getOrderStatus(int $orderId): ?array
    {
        $order = DB::connection(self::DB_CONNECTION)
            ->table('b_sale_order')
            ->where('ID', $orderId)
            ->select([
                'ID',
                'ACCOUNT_NUMBER',
                'USER_ID',
                'PRICE',
                'CURRENCY',
                'PAYED',
                'DATE_PAYED',
                'STATUS_ID',
                'DATE_INSERT',
            ])
            ->first();

        if ($order === null) {
            return null;
        }

        $payment = DB::connection(self::DB_CONNECTION)
            ->table('b_sale_order_payment')
            ->where('ORDER_ID', $orderId)
            ->orderByDesc('ID')
            ->select([
                'ID',
                'ORDER_ID',
                'PAY_SYSTEM_ID',
                'SUM',
                'CURRENCY',
                'PAID',
                'DATE_PAID',
                'PS_STATUS',
                'PS_STATUS_CODE',
                'PS_INVOICE_ID',
                'PS_RESPONSE_DATE',
            ])
            ->first();

        return [
            'order_id' => (int) $order->ID,
            'account_number' => (string) ($order->ACCOUNT_NUMBER ?? ''),
            'user_id' => (int) ($order->USER_ID ?? 0),
            'amount' => DecimalString::normalize((string) ($payment->SUM ?? $order->PRICE ?? '0')),
            'currency' => (string) ($payment->CURRENCY ?? $order->CURRENCY ?? 'RUB'),
            'payed' => (string) ($order->PAYED ?? 'N') === 'Y',
            'paid_at' => $order->DATE_PAYED ?? ($payment->DATE_PAID ?? null),
            'status_id' => (string) ($order->STATUS_ID ?? ''),
            'payment' => $payment ? [
                'id' => (int) $payment->ID,
                'pay_system_id' => (int) ($payment->PAY_SYSTEM_ID ?? 0),
                'paid' => (string) ($payment->PAID ?? 'N') === 'Y',
                'ps_status' => $payment->PS_STATUS ?? null,
                'ps_status_code' => $payment->PS_STATUS_CODE ?? null,
                'ps_invoice_id' => $payment->PS_INVOICE_ID ?? null,
                'ps_response_date' => $payment->PS_RESPONSE_DATE ?? null,
            ] : null,
        ];
    }
}
