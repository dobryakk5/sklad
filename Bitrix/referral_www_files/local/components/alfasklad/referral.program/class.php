<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

class AlfaskladReferralProgramComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        global $USER;

        if (!$USER->IsAuthorized()) {
            ShowError("Пользователь не авторизован");
            return;
        }

        $refCode = "REF-05-56783";

        $this->arResult = [
            "USER_ID" => (int)$USER->GetID(),

            // Пока заглушка. Потом заменить чтением из БД.
            "REF_CODE" => $refCode,
            "REFERRAL_URL" => "https://alfasklad.ru/?ref=" . rawurlencode($refCode),

            "DISCOUNT_TEXT" => "-33%",
            "DISCOUNT_PERIOD" => "на первые три месяца",

            "FRIENDS_TOTAL" => 12,
            "FRIENDS_ACTIVE" => 7,
            "BONUS_PAID" => 28000,
            "BONUS_PLANNED" => 12000,
        ];

        $this->includeComponentTemplate();
    }
}
