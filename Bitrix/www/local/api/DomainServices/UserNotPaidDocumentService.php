<?php
use Api\DomainServices\User\UserService;
use Api\Models\Contract;
use Api\Models\Document;
use Carbon\Carbon;

/**
 * Особый сервис, который подключается через require, чтобы можно было его использовать в агентах
 * Class UserNotPaidDocumentService
 */
class UserNotPaidDocumentService
{
    private static $usersTokensRepository = [];
    private static $contractRepository = [];

    const HOUR_FOR_SEND_PUSHES = '12';

    const DAY_1 = 1,
        DAY_5 = 5,
        DAY_12 = 12;

    /**
     *  Проверяет есть ли неоплаченные счета и отправляет по ним пуш уведомления пользователям
     * @return string
     */
    public static function CheckNotPaidDocuments()
    {
        $exitCode = '\UserNotPaidDocumentService::CheckNotPaidDocuments();';

        // Текущая дата по москве
        $currentDate = new Carbon('now', new \DateTimeZone('Europe/Moscow'));

        // Если сейчас не 12 часов, то пуши не отправляем
        if ($currentDate->hour != self::HOUR_FOR_SEND_PUSHES) {
            return $exitCode;
        }

        // Берём текущий день месяца
        $currentDay = $currentDate->day;

        $days = [
            self::DAY_1,
            self::DAY_5,
            self::DAY_12,
            $currentDate->daysInMonth,
        ];

        // Если текущий день месяца не 1, 5, 12 или не последний день месяца, то выходим
        if (!in_array($currentDay, $days)) {
            return true;
        }

        // Получаем неоплаченные счета пользователей
        $documents = Document::getActiveNotPaidNotExpired();

        $pushesData = [];

        // Формируем данные для пушей
        foreach ($documents as $document) {
            /**
             * @var Document $document
             */
            // Берём GUID договора
            $contractGUID = $document->getContractGUID();

            // Полуаем договоро из хранилища/БД
            if (array_key_exists($contractGUID, self::$contractRepository)) {
                $contract = self::$contractRepository[$contractGUID];
            } else {
                $contract = Contract::getByGUID($contractGUID);
                if (empty($contract)) {
                    continue;
                }
                self::$contractRepository[$contractGUID] = $contract;
            }

            $pushesData[] = [
                    'title' => 'Неоплаченный счет',
                    'text' => 'У вас есть неоплаченные счета. ' . $contract->getNumber(),
                    'id' => $contract->getId(),
                    'user' => $contract->getUserId()
                ];
        }

        $pushesSended = 0;
        // Рассылаем пуши пользователя
        foreach ($pushesData as $pushData) {
            $userId = $pushData['user'];
            if (array_key_exists($userId, self::$usersTokensRepository)) {
                $userTokens = self::$usersTokensRepository[$userId];
            } else {
                $user = UserService::getUserById($userId);
                if (empty($user)) {
                    continue;
                }
                $userTokens = $user->getTokens();
                self::$usersTokensRepository[$userId] = $userTokens;
            }

            if (empty($userTokens)) {
                continue;
            }

            $title = $pushData['title'];
            $text = $pushData['text'];
            $data = [
                'type' => \Api\DomainServices\PushesService::TYPE_PUSH_NOT_PAID_DOCUMENT,
                'id' =>$pushData['id'],
            ];

            foreach ($userTokens as $userToken) {
                \Api\DomainServices\PushesService::sendPush($userToken, $title, $text, $data);
                $pushesSended++;
            }
        }

        return $exitCode;
    }
}