<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;

class AlfaskladReferralProgramComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            ShowError("Пользователь не авторизован");
            return;
        }

        $userId = (int) $USER->GetID();

        $row = $this->getReferralRow($userId);

        if (!$row) {
            // Запись в HL-блоке ещё не создана — показываем "заглушку ожидания"
            $this->arResult = [
                "USER_ID"         => $userId,
                "STATUS"          => "PENDING",
                "REF_CODE"        => "",
                "REFERRAL_URL"    => "",
                "BONUS_AMOUNT"    => 0.0,
                "REFERRAL_COUNT"  => 0,
                "DATE_CREATE"     => "",
                "DISCOUNT_TEXT"   => "-33%",
                "DISCOUNT_PERIOD" => "на первые три месяца",
            ];
        } else {
            $refCode = (string) ($row["UF_REF_CODE"] ?? "");

            // UF_REFERRAL_COUNT — общее кол-во привлечённых из 1С.
            // Активные и детализация бонусов в HL-блоке не хранятся —
            // эти поля приходят из 1С единым числом.
            $referralCount = (int)   ($row["UF_REFERRAL_COUNT"] ?? 0);
            $bonusAmount   = (float) ($row["UF_BONUS_AMOUNT"]   ?? 0);

            $this->arResult = [
                "USER_ID"         => $userId,
                "STATUS"          => (string) ($row["UF_STATUS"]       ?? "PENDING"),
                "REF_CODE"        => $refCode,
                "REFERRAL_URL"    => (string) ($row["UF_REFERRAL_URL"] ?? ""),
                "DATE_CREATE"     => (string) ($row["UF_DATE_CREATE"]  ?? ""),
                "DISCOUNT_TEXT"   => "-33%",
                "DISCOUNT_PERIOD" => "на первые три месяца",

                // Поля статистики — маппинг из HL на имена, которые ждёт шаблон
                "REFERRAL_COUNT"  => $referralCount,
                "BONUS_AMOUNT"    => $bonusAmount,

                // FRIENDS_ACTIVE и BONUS_PLANNED в HL-блоке не хранятся.
                // Пока показываем прочерк; когда 1С начнёт слать эти поля —
                // добавить UF_FRIENDS_ACTIVE / UF_BONUS_PLANNED в HL и сюда.
                "FRIENDS_ACTIVE"  => null,
                "BONUS_PLANNED"   => null,
            ];
        }

        $this->includeComponentTemplate();
    }

    /**
     * Возвращает строку HL-блока ReferralProfile для пользователя
     * или false если запись не найдена.
     *
     * @param int $userId
     * @return array|false
     */
    private function getReferralRow(int $userId)
    {
        if (!$userId) {
            return false;
        }

        if (!defined("HL_REFERRAL_ID") || !HL_REFERRAL_ID) {
            // Константа не задана — значит миграция ещё не запускалась
            return false;
        }

        if (!Loader::includeModule("highloadblock")) {
            return false;
        }

        try {
            $hlBlock = HighloadBlockTable::getById(HL_REFERRAL_ID)->fetch();
            if (!$hlBlock) {
                return false;
            }

            $entity      = HighloadBlockTable::compileEntity($hlBlock);
            $dataClass   = $entity->getDataClass();

            $row = $dataClass::getList([
                "filter" => ["=UF_USER_ID" => $userId],
                "select" => [
                    "UF_REF_CODE",
                    "UF_STATUS",
                    "UF_BONUS_AMOUNT",
                    "UF_REFERRAL_URL",
                    "UF_DATE_CREATE",
                    "UF_REFERRAL_COUNT",
                ],
                "limit"  => 1,
            ])->fetch();

            return $row ?: false;

        } catch (\Exception $e) {
            // Молча возвращаем false — шаблон покажет состояние "ожидания"
            return false;
        }
    }
}
