<?php

namespace App\Services\DebtPayments;

use App\DTO\DebtPayments\DebtPaymentCandidate;
use App\Models\DebtPaymentCampaign;
use App\Models\DebtPaymentLink;
use Carbon\CarbonImmutable;

final class DebtPaymentLinkService
{
    public function findOrCreateCampaign(CarbonImmutable $date, bool $force = false): DebtPaymentCampaign
    {
        $campaign = DebtPaymentCampaign::query()
            ->where('source', (string) config('debt-payments.source', 'monthly_debt'))
            ->whereDate('campaign_date', $date->toDateString())
            ->first();

        if ($campaign === null) {
            $campaign = DebtPaymentCampaign::create([
                'source' => (string) config('debt-payments.source', 'monthly_debt'),
                'campaign_date' => $date->toDateString(),
                'status' => DebtPaymentCampaign::STATUS_DRAFT,
            ]);
        }

        if ($force && $campaign->status !== DebtPaymentCampaign::STATUS_CANCELLED) {
            $campaign->forceFill([
                'status' => DebtPaymentCampaign::STATUS_DRAFT,
                'started_at' => null,
                'finished_at' => null,
                'failed_at' => null,
                'last_error' => null,
            ])->save();
        }

        return $campaign;
    }

    public function createLink(DebtPaymentCampaign $campaign, DebtPaymentCandidate $candidate): DebtPaymentLink
    {
        $existing = DebtPaymentLink::query()
            ->where('campaign_id', $campaign->id)
            ->where('invoice_id', $candidate->invoiceId)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        return DebtPaymentLink::create([
            'campaign_id' => $campaign->id,
            'token' => $this->generateUniqueToken(),
            'bitrix_user_id' => $candidate->bitrixUserId,
            'customer_name' => $candidate->customerName,
            'customer_email' => $candidate->email,
            'customer_phone' => $candidate->phone,
            'contract_id' => $candidate->contractId,
            'contract_number' => $candidate->contractNumber,
            'contract_guid' => $candidate->contractGuid,
            'invoice_id' => $candidate->invoiceId,
            'invoice_number' => $candidate->invoiceNumber,
            'invoice_guid' => $candidate->invoiceGuid,
            'amount' => $candidate->amount,
            'currency' => $candidate->currency,
            'status' => DebtPaymentLink::STATUS_NEW,
            'expires_at' => CarbonImmutable::parse($campaign->campaign_date)
                ->addDays((int) config('debt-payments.link_ttl_days', 30))
                ->endOfDay(),
        ]);
    }

    public function paymentUrl(DebtPaymentLink $link): string
    {
        return rtrim((string) config('debt-payments.public_base_url', 'https://alfasklad.ru'), '/')
            . '/pay/' . $link->token;
    }

    public function generateUniqueToken(): string
    {
        do {
            $token = bin2hex(random_bytes(16));
        } while (DebtPaymentLink::where('token', $token)->exists());

        return $token;
    }
}
