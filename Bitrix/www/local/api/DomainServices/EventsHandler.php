<?php

namespace Api\DomainServices;

/**
 * Обработчик событий
 * Class EventsHandler
 * @package Api\DomainServices
 */
class EventsHandler
{
    /**
     * Обработка события после создания элемента в инфоблоке
     * @param $arFields
     */
    public static function OnAfterIBlockElementAdd(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == 55) {
            self::OnAfterUserInformSectionElementCreated($arFields);
        }
    }

    /**
     * Событие после создания элемента в информационном разделе личного кабинета
     * @param $arFields
     * @return bool
     */
    public static function OnAfterUserInformSectionElementCreated($arFields)
    {
        if ($arFields['ACTIVE'] == 'N') {
            return true;
        }
        $title = 'Новая запись в Информационном разделе';
        $text = $arFields['NAME'];
        $data = [
            'type' => PushesService::TYPE_PUSH_NEW_INFORM_RECORD,
            'id' => $arFields['ID']
        ];
        PushesService::sendPushToTopic(PushesService::TOPIC_GENERAL, $title, $text, $data);
    }
}