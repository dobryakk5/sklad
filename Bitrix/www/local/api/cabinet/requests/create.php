<?php

require_once dirname(__DIR__) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$userId = (int) ($data['user_id'] ?? 0);
$type = trim((string) ($data['type'] ?? ''));
$fields = $data['fields'] ?? [];

if ($userId <= 0 || $type === '' || ! is_array($fields)) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

$config = cabinet_request_config($type);

if ($config === null) {
    cabinet_error('UNKNOWN_REQUEST_TYPE', 'Unknown request type', 422);
}

cabinet_authorize_user($userId);

$schema = cabinet_form_schema((int) $config['form_id']);

if ($schema === []) {
    cabinet_error('BITRIX_ERROR', 'Form schema is empty', 502);
}

$formValues = cabinet_build_form_values($schema, $config, $fields, $userId);
$resultId = CFormResult::Add((int) $config['form_id'], $formValues, 'N');

if (! $resultId) {
    $message = trim((string) ($GLOBALS['strError'] ?? ''));
    cabinet_error('BITRIX_ERROR', $message !== '' ? $message : 'Form result was not created', 502);
}

CFormResult::SetEvent($resultId);
CFormResult::Mail($resultId);

cabinet_success([
    'result_id' => (int) $resultId,
    'web_form_id' => (int) $config['form_id'],
]);

function cabinet_request_config(string $type): ?array
{
    $configs = [
        'callback' => [
            'form_id' => 14,
            'map' => [
                'NAME' => ['name'],
                'PHONE' => ['phone'],
                'EMAIL' => ['email'],
                'PROMO' => ['callback_time', 'message'],
            ],
        ],
        'manager_order' => [
            'form_id' => 14,
            'map' => [
                'NAME' => ['name'],
                'PHONE' => ['phone'],
                'EMAIL' => ['email'],
                'PROMO' => ['promo', 'message'],
            ],
        ],
        'question' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'profile_edit' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'video' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'storage' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'repair' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'cancel-contract' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'board' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'review' => [
            'form_id' => 20,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
        'waiting-list' => [
            'form_id' => 15,
            'map' => [
                'NAME' => ['name'],
                'PHONE' => ['phone'],
                'SKLAD' => ['warehouse', 'storage', 'sklad'],
                'SQUARE' => ['square', 'space', 'box_name'],
            ],
        ],
        'waiting_list' => [
            'form_id' => 15,
            'map' => [
                'NAME' => ['name'],
                'PHONE' => ['phone'],
                'SKLAD' => ['warehouse', 'storage', 'sklad'],
                'SQUARE' => ['square', 'space', 'box_name'],
            ],
        ],
        'request_upd' => [
            'form_id' => 16,
            'map' => cabinet_invoice_request_map(),
        ],
        'request_invoice_email' => [
            'form_id' => 22,
            'map' => cabinet_invoice_request_map(),
        ],
        'delivery' => [
            'form_id' => 23,
            'map' => [
                'NAME' => ['name'],
                'PHONE' => ['phone'],
                'EMAIL' => ['email'],
                'DATE' => ['date'],
                'ACTION_TYPE' => ['action_type'],
                'LOCATION_FROM_1' => ['location_from', 'address_from'],
                'LOCATION_FROM_2' => ['warehouse_from'],
                'LOCATION_TO_1' => ['warehouse_to'],
                'LOCATION_TO_2' => ['location_to', 'address_to'],
                'CARGO' => ['cargo', 'message'],
                'PACKING' => ['packing'],
            ],
        ],
        'rent_calc' => [
            'form_id' => 25,
            'map' => [
                'EMAIL' => ['email'],
                'PHONE' => ['phone'],
                'NAME' => ['name'],
                'WITH_PACKAGE' => ['with_package'],
                'WITH_DELIVERY' => ['with_delivery'],
                'RENT_DURATION' => ['rent_duration'],
                'SIZE' => ['size', 'space', 'volume'],
                'SIZE_TYPE' => ['size_type'],
                'STORAGE' => ['warehouse', 'storage'],
            ],
        ],
        'rent_request' => [
            'form_id' => 26,
            'map' => [
                'STORAGE' => ['warehouse', 'storage'],
                'VOLUME' => ['volume'],
                'SPACE' => ['space', 'square'],
                'NAME' => ['name'],
                'PHONE' => ['phone'],
                'EMAIL' => ['email'],
            ],
        ],
        'mobile_feedback' => [
            'form_id' => 27,
            'message_sid' => 'MESSAGE',
            'map' => cabinet_common_message_map(),
        ],
    ];

    return $configs[$type] ?? null;
}

function cabinet_common_message_map(): array
{
    return [
        'NAME' => ['name'],
        'EMAIL' => ['email'],
        'PHONE' => ['phone'],
        'SKLAD' => ['warehouse', 'storage', 'sklad'],
        'STORAGE' => ['warehouse', 'storage', 'sklad'],
        'BOX_NAME' => ['box_name', 'box'],
        'CONTRACT_NUMBER' => ['contract_number'],
        'DATE' => ['date'],
        'MESSAGE' => ['message'],
    ];
}

function cabinet_invoice_request_map(): array
{
    return [
        'INVOICE_NUMBER' => ['invoice_number'],
        'INVOICE_GUID' => ['invoice_guid'],
        'CONTRACT_NUMBER' => ['contract_number'],
        'CONTRACT_GUID' => ['contract_guid'],
        'USER_EMAIL' => ['email', 'user_email'],
    ];
}

function cabinet_form_schema(int $formId): array
{
    global $DB;

    $schema = [];
    $sql = "
        SELECT
            f.SID,
            f.REQUIRED,
            a.ID AS ANSWER_ID,
            a.FIELD_TYPE AS ANSWER_TYPE,
            a.MESSAGE AS ANSWER_MESSAGE
        FROM b_form_field f
        INNER JOIN b_form_answer a ON a.FIELD_ID = f.ID
        WHERE f.FORM_ID = " . $formId . "
        ORDER BY f.C_SORT, f.ID, a.C_SORT, a.ID
    ";
    $rs = $DB->Query($sql);

    while ($row = $rs->Fetch()) {
        $sid = (string) $row['SID'];

        if (! isset($schema[$sid])) {
            $schema[$sid] = [
                'sid' => $sid,
                'required' => ($row['REQUIRED'] ?? 'N') === 'Y',
                'answers' => [],
            ];
        }

        $schema[$sid]['answers'][] = [
            'id' => (int) $row['ANSWER_ID'],
            'type' => (string) $row['ANSWER_TYPE'],
            'message' => trim((string) $row['ANSWER_MESSAGE']),
        ];
    }

    return $schema;
}

function cabinet_build_form_values(array $schema, array $config, array $fields, int $userId): array
{
    $values = [];

    foreach ($schema as $sid => $field) {
        $value = cabinet_field_value($sid, $config, $fields);

        if (($config['message_sid'] ?? null) === $sid) {
            $composedMessage = cabinet_compose_message($fields);
            $value = ! cabinet_is_empty($composedMessage) ? $composedMessage : $value;
        }

        if (cabinet_is_empty($value) && $field['required']) {
            $value = cabinet_required_fallback($sid, $field, $fields, $userId);
        }

        if ($sid === 'DATE') {
            $value = cabinet_normalize_date($value);
        }

        if (cabinet_is_empty($value)) {
            continue;
        }

        cabinet_apply_answer_value($values, $field, $value);
    }

    return $values;
}

function cabinet_field_value(string $sid, array $config, array $fields): string
{
    $sources = $config['map'][$sid] ?? [strtolower($sid), $sid];

    foreach ($sources as $source) {
        if (isset($fields[$source]) && ! cabinet_is_empty($fields[$source])) {
            return trim((string) $fields[$source]);
        }
    }

    return '';
}

function cabinet_compose_message(array $fields): string
{
    $labels = [
        'request_title' => 'Тип обращения',
        'message' => 'Сообщение',
        'service' => 'Услуга',
        'callback_time' => 'Удобное время для звонка',
        'company' => 'Компания',
        'rating' => 'Оценка',
        'warehouse' => 'Склад',
        'box_name' => 'Бокс',
        'contract_number' => 'Номер договора',
        'invoice_number' => 'Номер счета',
    ];
    $lines = [];

    foreach ($labels as $key => $label) {
        if (isset($fields[$key]) && ! cabinet_is_empty($fields[$key])) {
            $lines[] = $label . ': ' . trim((string) $fields[$key]);
        }
    }

    return implode("\n", $lines);
}

function cabinet_required_fallback(string $sid, array $field, array $fields, int $userId): string
{
    if (in_array($sid, ['EMAIL', 'USER_EMAIL'], true)) {
        return filter_var($fields['email'] ?? '', FILTER_VALIDATE_EMAIL)
            ? trim((string) $fields['email'])
            : 'no-reply@alfasklad.ru';
    }

    if ($sid === 'NAME') {
        return ! cabinet_is_empty($fields['name'] ?? '')
            ? trim((string) $fields['name'])
            : 'Пользователь #' . $userId;
    }

    if ($sid === 'DATE') {
        return date('d.m.Y');
    }

    return 'Не указано';
}

function cabinet_normalize_date(string $value): string
{
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        $timestamp = strtotime($value);

        if ($timestamp !== false) {
            return date('d.m.Y', $timestamp);
        }
    }

    return $value;
}

function cabinet_apply_answer_value(array &$values, array $field, string $value): void
{
    $answer = $field['answers'][0] ?? null;

    if ($answer === null) {
        return;
    }

    $answerType = $answer['type'];
    $sid = $field['sid'];

    if (in_array($answerType, ['dropdown', 'radio', 'checkbox'], true)) {
        $answerId = cabinet_select_answer_id($field['answers'], $value);

        if ($answerId <= 0) {
            return;
        }

        if ($answerType === 'checkbox') {
            $values['form_checkbox_' . $sid] = [$answerId];
            return;
        }

        $values['form_' . $answerType . '_' . $sid] = $answerId;
        return;
    }

    $values['form_' . $answerType . '_' . $answer['id']] = $value;
}

function cabinet_select_answer_id(array $answers, string $value): int
{
    if (ctype_digit($value)) {
        return (int) $value;
    }

    foreach ($answers as $answer) {
        if (strcasecmp(trim($answer['message']), trim($value)) === 0) {
            return (int) $answer['id'];
        }
    }

    return (int) ($answers[0]['id'] ?? 0);
}

function cabinet_is_empty(mixed $value): bool
{
    return trim((string) $value) === '';
}
