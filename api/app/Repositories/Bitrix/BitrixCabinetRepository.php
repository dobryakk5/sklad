<?php

namespace App\Repositories\Bitrix;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

final class BitrixCabinetRepository
{
    private const DB_CONNECTION = 'bitrix';

    private const CONTRACTS_IBLOCK_ID = 52;
    private const INVOICES_IBLOCK_ID = 53;
    private const PAYMENT_METHODS_IBLOCK_ID = 69;
    private const BOXES_IBLOCK_ID = 40;
    private const BASE_PRICE_GROUP_ID = 1;

    private const CONTRACT_PROP_BOX = 509;
    private const CONTRACT_PROP_USER = 511;
    private const CONTRACT_PROP_NUMBER = 510;
    private const CONTRACT_PROP_BALANCE = 514;
    private const CONTRACT_PROP_GUID = 516;
    private const CONTRACT_PROP_STATUS = 517;

    private const INVOICE_PROP_NUMBER = 518;
    private const INVOICE_PROP_DATE_CREATE = 519;
    private const INVOICE_PROP_CONTRACT_NUMBER = 520;
    private const INVOICE_PROP_DATE_FROM = 521;
    private const INVOICE_PROP_STATUS = 524;
    private const INVOICE_PROP_USER = 525;
    private const INVOICE_PROP_CONTRACT_GUID = 526;
    private const INVOICE_PROP_PROFILE_TYPE = 596;
    private const INVOICE_PROP_TOTAL_AMOUNT = 670;

    private const PAYMENT_METHOD_PROP_STATUS = 684;
    private const PAYMENT_METHOD_PROP_SAVED = 685;
    private const PAYMENT_METHOD_PROP_AUTOPAY = 690;

    private const CONTRACT_STATUS_ACTIVE = 352;
    private const CONTRACT_STATUS_INACTIVE = 353;

    private const INVOICE_STATUS_NOT_PAID = 354;
    private const INVOICE_STATUS_PAID = 355;
    private const INVOICE_STATUS_PARTIAL = 356;
    private const INVOICE_STATUS_CANCELLED = 400;
    private const INVOICE_STATUS_PROCESSING = 421;

    public function findUserById(int $userId): ?array
    {
        $user = DB::connection(self::DB_CONNECTION)
            ->table('b_user')
            ->select([
                'ID',
                'LOGIN',
                'EMAIL',
                'NAME',
                'LAST_NAME',
                'PERSONAL_PHONE',
                'PERSONAL_MOBILE',
                'ACTIVE',
            ])
            ->where('ID', $userId)
            ->where('ACTIVE', 'Y')
            ->first();

        if ($user === null) {
            return null;
        }

        return $this->formatUser($user);
    }

    private function formatUser(object $user): array
    {
        return [
            'id' => (int) $user->ID,
            'name' => trim(($user->NAME ?? '') . ' ' . ($user->LAST_NAME ?? '')),
            'email' => (string) ($user->EMAIL ?? ''),
            'phone' => (string) (($user->PERSONAL_PHONE ?: $user->PERSONAL_MOBILE) ?? ''),
        ];
    }

    public function getContracts(int $userId): array
    {
        $rows = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as e')
            ->join('b_iblock_element_prop_s52 as p', 'p.IBLOCK_ELEMENT_ID', '=', 'e.ID')
            ->leftJoin('b_iblock_element as box', 'box.ID', '=', DB::raw('p.PROPERTY_' . self::CONTRACT_PROP_BOX))
            ->where('e.IBLOCK_ID', self::CONTRACTS_IBLOCK_ID)
            ->where('e.ACTIVE', 'Y')
            ->where(DB::raw('p.PROPERTY_' . self::CONTRACT_PROP_USER), (string) $userId)
            ->orderByDesc('e.ID')
            ->selectRaw('
                e.ID as id,
                p.PROPERTY_' . self::CONTRACT_PROP_NUMBER . ' as contract_number,
                p.PROPERTY_' . self::CONTRACT_PROP_STATUS . ' as status_id,
                p.PROPERTY_' . self::CONTRACT_PROP_BALANCE . ' as raw_balance,
                p.PROPERTY_' . self::CONTRACT_PROP_BOX . ' as box_id,
                p.PROPERTY_' . self::CONTRACT_PROP_GUID . ' as contract_guid,
                box.NAME as box_name
            ')
            ->get();

        return $rows
            ->map(fn (object $row) => [
                'id' => (int) $row->id,
                'number' => (string) ($row->contract_number ?? ''),
                'status' => $this->mapContractStatus((int) $row->status_id),
                'balance' => $this->normalizeContractBalance($row->raw_balance),
                'box_id' => $row->box_id !== null ? (int) $row->box_id : null,
                'box_name' => $row->box_name ?: null,
                'contract_guid' => (string) ($row->contract_guid ?? ''),
            ])
            ->all();
    }

    public function getInvoices(int $userId): array
    {
        $rows = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as invoice')
            ->join('b_iblock_element_prop_s53 as p53', 'p53.IBLOCK_ELEMENT_ID', '=', 'invoice.ID')
            ->leftJoin('b_catalog_price as price', function ($join) {
                $join->on('price.PRODUCT_ID', '=', 'invoice.ID')
                    ->where('price.CATALOG_GROUP_ID', '=', self::BASE_PRICE_GROUP_ID);
            })
            ->leftJoin('b_iblock_element_prop_s52 as p52', DB::raw('p52.PROPERTY_' . self::CONTRACT_PROP_GUID), '=', DB::raw('p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_GUID))
            ->leftJoin('b_iblock_element as contract', 'contract.ID', '=', 'p52.IBLOCK_ELEMENT_ID')
            ->where('invoice.IBLOCK_ID', self::INVOICES_IBLOCK_ID)
            ->where('invoice.ACTIVE', 'Y')
            ->where(DB::raw('p53.PROPERTY_' . self::INVOICE_PROP_USER), (string) $userId)
            ->orderByDesc('invoice.ID')
            ->selectRaw('
                invoice.ID as id,
                p53.PROPERTY_' . self::INVOICE_PROP_NUMBER . ' as invoice_number,
                p53.PROPERTY_' . self::INVOICE_PROP_STATUS . ' as status_id,
                p53.PROPERTY_' . self::INVOICE_PROP_TOTAL_AMOUNT . ' as total_invoice_amount,
                p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_NUMBER . ' as contract_number,
                p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_GUID . ' as contract_guid,
                p53.PROPERTY_' . self::INVOICE_PROP_DATE_CREATE . ' as created_at,
                p53.PROPERTY_' . self::INVOICE_PROP_DATE_FROM . ' as date_from,
                price.PRICE as current_price,
                contract.ID as contract_id
            ')
            ->get();

        return $rows
            ->map(fn (object $row) => [
                'id' => (int) $row->id,
                'number' => (string) ($row->invoice_number ?? ''),
                'status' => $this->mapInvoiceStatus((int) $row->status_id),
                'amount' => $this->resolveInvoiceAmount($row->current_price, $row->total_invoice_amount),
                'currency' => 'RUB',
                'contract_id' => $row->contract_id !== null ? (int) $row->contract_id : null,
                'contract_number' => (string) ($row->contract_number ?? ''),
                'created_at' => $this->normalizeDate($row->created_at),
                'contract_guid' => (string) ($row->contract_guid ?? ''),
                'date_from' => $this->normalizeDate($row->date_from),
            ])
            ->all();
    }

    public function getInvoiceById(int $invoiceId): ?array
    {
        $row = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as invoice')
            ->join('b_iblock_element_prop_s53 as p53', 'p53.IBLOCK_ELEMENT_ID', '=', 'invoice.ID')
            ->leftJoin('b_catalog_price as price', function ($join) {
                $join->on('price.PRODUCT_ID', '=', 'invoice.ID')
                    ->where('price.CATALOG_GROUP_ID', '=', self::BASE_PRICE_GROUP_ID);
            })
            ->leftJoin('b_iblock_element_prop_s52 as p52', DB::raw('p52.PROPERTY_' . self::CONTRACT_PROP_GUID), '=', DB::raw('p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_GUID))
            ->leftJoin('b_iblock_element as contract', 'contract.ID', '=', 'p52.IBLOCK_ELEMENT_ID')
            ->where('invoice.IBLOCK_ID', self::INVOICES_IBLOCK_ID)
            ->where('invoice.ACTIVE', 'Y')
            ->where('invoice.ID', $invoiceId)
            ->selectRaw('
                invoice.ID as id,
                p53.PROPERTY_' . self::INVOICE_PROP_NUMBER . ' as invoice_number,
                p53.PROPERTY_' . self::INVOICE_PROP_STATUS . ' as status_id,
                p53.PROPERTY_' . self::INVOICE_PROP_TOTAL_AMOUNT . ' as total_invoice_amount,
                p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_NUMBER . ' as contract_number,
                p53.PROPERTY_' . self::INVOICE_PROP_CONTRACT_GUID . ' as contract_guid,
                p53.PROPERTY_' . self::INVOICE_PROP_DATE_CREATE . ' as created_at,
                p53.PROPERTY_' . self::INVOICE_PROP_DATE_FROM . ' as date_from,
                p53.PROPERTY_' . self::INVOICE_PROP_USER . ' as owner_user_id,
                price.PRICE as current_price,
                contract.ID as contract_id
            ')
            ->first();

        if ($row === null) {
            return null;
        }

        return [
            'id' => (int) $row->id,
            'number' => (string) ($row->invoice_number ?? ''),
            'status' => $this->mapInvoiceStatus((int) $row->status_id),
            'amount' => $this->resolveInvoiceAmount($row->current_price, $row->total_invoice_amount),
            'currency' => 'RUB',
            'contract_id' => $row->contract_id !== null ? (int) $row->contract_id : null,
            'contract_number' => (string) ($row->contract_number ?? ''),
            'created_at' => $this->normalizeDate($row->created_at),
            'items' => [],
            'owner_user_id' => (int) ($row->owner_user_id ?? 0),
        ];
    }

    public function getBalance(int $userId): array
    {
        $contracts = collect($this->getContracts($userId));

        $activeContracts = $contracts->where('status', 'active')->values();
        $totalBalance = round((float) $activeContracts->sum('balance'), 2);
        $totalDebt = round((float) $activeContracts->sum(
            fn (array $contract) => $contract['balance'] < 0 ? abs($contract['balance']) : 0
        ), 2);

        return [
            'total_balance' => $totalBalance,
            'total_debt' => $totalDebt,
            'by_contract' => $activeContracts
                ->map(fn (array $contract) => [
                    'contract_id' => $contract['id'],
                    'contract_number' => $contract['number'],
                    'balance' => $contract['balance'],
                ])
                ->all(),
        ];
    }

    public function getPaymentMethod(int $userId): ?array
    {
        $paymentMethodId = DB::connection(self::DB_CONNECTION)
            ->table('b_uts_user')
            ->where('VALUE_ID', $userId)
            ->value('UF_AUTOPAYMEN_METHOD');

        if (! $paymentMethodId) {
            return null;
        }

        $row = DB::connection(self::DB_CONNECTION)
            ->table('b_iblock_element as e')
            ->leftJoin('b_iblock_element_property as p', function ($join) {
                $join->on('p.IBLOCK_ELEMENT_ID', '=', 'e.ID')
                    ->whereIn('p.IBLOCK_PROPERTY_ID', [
                        self::PAYMENT_METHOD_PROP_STATUS,
                        self::PAYMENT_METHOD_PROP_SAVED,
                        self::PAYMENT_METHOD_PROP_AUTOPAY,
                    ]);
            })
            ->where('e.IBLOCK_ID', self::PAYMENT_METHODS_IBLOCK_ID)
            ->where('e.ID', (int) $paymentMethodId)
            ->groupBy('e.ID', 'e.NAME', 'e.ACTIVE')
            ->selectRaw('
                e.ID as id,
                e.NAME as method_name,
                e.ACTIVE as active_flag,
                MAX(CASE WHEN p.IBLOCK_PROPERTY_ID = ' . self::PAYMENT_METHOD_PROP_STATUS . ' THEN p.VALUE END) as pm_status,
                MAX(CASE WHEN p.IBLOCK_PROPERTY_ID = ' . self::PAYMENT_METHOD_PROP_SAVED . ' THEN p.VALUE END) as pm_saved,
                MAX(CASE WHEN p.IBLOCK_PROPERTY_ID = ' . self::PAYMENT_METHOD_PROP_AUTOPAY . ' THEN p.VALUE END) as autopay
            ')
            ->first();

        if ($row === null) {
            return null;
        }

        $saved = $this->isTruthy($row->pm_saved);
        $active = $row->active_flag === 'Y' && (string) $row->pm_status === 'active' && $saved;

        return [
            'id' => (int) $row->id,
            'status' => $active ? 'active' : 'pending',
            'autopay_enabled' => $this->isTruthy($row->autopay),
            'card_last4' => $this->extractLast4((string) ($row->method_name ?? '')),
        ];
    }

    private function mapContractStatus(int $statusId): string
    {
        $activeStatusId = (int) config('bitrix.contract_status_active_id', self::CONTRACT_STATUS_ACTIVE);

        return $statusId === $activeStatusId ? 'active' : 'inactive';
    }

    private function mapInvoiceStatus(int $statusId): string
    {
        return (string) Arr::get(
            config('bitrix.invoice_status_map', []),
            $statusId,
            match ($statusId) {
                self::INVOICE_STATUS_NOT_PAID => 'not_paid',
                self::INVOICE_STATUS_PARTIAL => 'partial',
                self::INVOICE_STATUS_PAID => 'paid',
                self::INVOICE_STATUS_PROCESSING => 'processing',
                self::INVOICE_STATUS_CANCELLED => 'not_paid',
                default => 'not_paid',
            }
        );
    }

    private function normalizeContractBalance(mixed $rawBalance): float
    {
        $balance = round($this->normalizeAmount($rawBalance) * -1, 2);

        return $balance == 0.0 ? 0.0 : $balance;
    }

    private function normalizeAmount(mixed $value): float
    {
        $normalized = str_replace([' ', ','], ['', '.'], (string) ($value ?? '0'));

        return round((float) $normalized, 2);
    }

    private function normalizeDate(mixed $value): ?string
    {
        $date = trim((string) ($value ?? ''));

        return $date !== '' ? $date : null;
    }

    private function resolveInvoiceAmount(mixed $currentPrice, mixed $totalAmount): float
    {
        $remaining = $this->normalizeAmount($currentPrice);

        if ($remaining > 0) {
            return $remaining;
        }

        return $this->normalizeAmount($totalAmount);
    }

    private function isTruthy(mixed $value): bool
    {
        return in_array((string) $value, ['1', 'Y', 'y', 'true', 'active'], true);
    }

    private function extractLast4(string $token): ?string
    {
        if (preg_match('/(\d{4})$/', $token, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
