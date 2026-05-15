<?php

namespace App\Services\DebtPayments;

use App\DTO\DebtPayments\DebtPaymentCandidate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BitrixDebtSource
{
    private const DB_CONNECTION = 'bitrix';
    private const INVOICES_IBLOCK_ID = 53;
    private const CONTRACTS_IBLOCK_ID = 52;
    private const BASE_PRICE_GROUP_ID = 1;

    private const INVOICE_PROP_NUMBER = 518;
    private const INVOICE_PROP_STATUS = 524;
    private const INVOICE_PROP_USER = 525;
    private const INVOICE_PROP_CONTRACT_GUID = 526;
    private const INVOICE_PROP_CONTRACT_NUMBER = 520;
    private const INVOICE_PROP_TOTAL_AMOUNT = 670;
    private const INVOICE_PROP_GUID = 527;

    private const CONTRACT_PROP_GUID = 516;

    private const INVOICE_STATUS_NOT_PAID = 354;

    /**
     * @return Collection<int, DebtPaymentCandidate>
     */
    public function findDebtorsForDate(CarbonImmutable $date): Collection
    {
        $invoiceGuidSelect = Schema::connection(self::DB_CONNECTION)
            ->hasColumn('b_iblock_element_prop_s53', 'PROPERTY_' . self::INVOICE_PROP_GUID)
            ? 'p53.PROPERTY_' . self::INVOICE_PROP_GUID . ' as invoice_guid'
            : 'NULL as invoice_guid';

        $amountExpr = 'COALESCE(NULLIF(price.PRICE, 0), NULLIF(p53.PROPERTY_' . self::INVOICE_PROP_TOTAL_AMOUNT . ", ''))";
        $normalizedAmountExpr = "CAST(REPLACE(REPLACE($amountExpr, ' ', ''), ',', '.') AS DECIMAL(12,2))";

        $rows = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as invoice')
            ->join('b_iblock_element_prop_s53 as p53', 'p53.IBLOCK_ELEMENT_ID', '=', 'invoice.ID')
            ->join('b_user as u', 'u.ID', '=', DB::raw('p53.PROPERTY_' . self::INVOICE_PROP_USER))
            ->leftJoin('b_catalog_price as price', function ($join): void {
                $join->on('price.PRODUCT_ID', '=', 'invoice.ID')
                    ->where('price.CATALOG_GROUP_ID', '=', self::BASE_PRICE_GROUP_ID);
            })
            ->leftJoin('b_iblock_element_prop_s52 as p52', DB::raw('p52.PROPERTY_' . self::CONTRACT_PROP_GUID), '=', DB::raw('p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_GUID))
            ->leftJoin('b_iblock_element as contract', function ($join): void {
                $join->on('contract.ID', '=', 'p52.IBLOCK_ELEMENT_ID')
                    ->where('contract.IBLOCK_ID', '=', self::CONTRACTS_IBLOCK_ID)
                    ->where('contract.ACTIVE', '=', 'Y');
            })
            ->where('invoice.IBLOCK_ID', self::INVOICES_IBLOCK_ID)
            ->where('invoice.ACTIVE', 'Y')
            ->where(DB::raw('p53.PROPERTY_' . self::INVOICE_PROP_STATUS), self::INVOICE_STATUS_NOT_PAID)
            ->where('u.ACTIVE', 'Y')
            ->whereRaw($normalizedAmountExpr . ' > 0')
            ->orderBy('invoice.ID')
            ->selectRaw('
                u.ID as bitrix_user_id,
                TRIM(CONCAT_WS(" ", u.LAST_NAME, u.NAME, u.SECOND_NAME)) as customer_name,
                u.EMAIL as email,
                COALESCE(NULLIF(u.PERSONAL_PHONE, ""), NULLIF(u.PERSONAL_MOBILE, "")) as phone,
                invoice.ID as invoice_id,
                p53.PROPERTY_' . self::INVOICE_PROP_NUMBER . ' as invoice_number,
                ' . $invoiceGuidSelect . ',
                contract.ID as contract_id,
                p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_NUMBER . ' as contract_number,
                p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_GUID . ' as contract_guid,
                ' . $amountExpr . ' as amount,
                COALESCE(price.CURRENCY, "RUB") as currency
            ')
            ->get();

        return $rows->map(fn (object $row) => new DebtPaymentCandidate(
            bitrixUserId: (int) $row->bitrix_user_id,
            customerName: trim((string) ($row->customer_name ?: 'Клиент')),
            email: $this->emptyToNull($row->email ?? null),
            phone: $this->emptyToNull($row->phone ?? null),
            invoiceId: (int) $row->invoice_id,
            invoiceNumber: (string) ($row->invoice_number ?: $row->invoice_id),
            invoiceGuid: $this->emptyToNull($row->invoice_guid ?? null),
            contractId: $row->contract_id !== null ? (int) $row->contract_id : null,
            contractNumber: (string) ($row->contract_number ?? ''),
            contractGuid: $this->emptyToNull($row->contract_guid ?? null),
            amount: DecimalString::normalize($row->amount ?? '0'),
            currency: (string) ($row->currency ?: 'RUB'),
        ));
    }

    private function emptyToNull(mixed $value): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : $value;
    }
}
