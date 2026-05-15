<?php

require_once __DIR__ . '/_config.php';

define('NO_KEEP_STATISTIC', true);
define('BX_WITH_ON_AFTER_EPILOG', false);
define('NO_AGENT_CHECK', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('form');

header('Content-Type: application/json; charset=utf-8');

function cabinet_verify_request(): array
{
    if (! in_array($_SERVER['REMOTE_ADDR'] ?? '', CABINET_ALLOWED_IPS, true)) {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    $timestamp = $_SERVER['HTTP_X_CABINET_TIMESTAMP'] ?? '';
    $signature = $_SERVER['HTTP_X_CABINET_SIGNATURE'] ?? '';

    if (! $timestamp || ! $signature || abs(time() - (int) $timestamp) > 60) {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    $rawBody = file_get_contents('php://input');

    if (! hash_equals(
        hash_hmac('sha256', $rawBody . $timestamp, CABINET_SERVICE_SECRET),
        $signature
    )) {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    $data = json_decode($rawBody, true);

    if (! is_array($data)) {
        cabinet_error('FORBIDDEN', 'Forbidden', 400);
    }

    return $data;
}

function cabinet_success(array $data): void
{
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function cabinet_require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }
}

function cabinet_error(
    int|string $arg1,
    ?string $arg2 = null,
    ?int $arg3 = null,
): void {
    if (is_int($arg1)) {
        $httpCode = $arg1;
        $code = $arg2 ?? 'FORBIDDEN';
        $message = null;
    } else {
        $code = $arg1;
        $message = $arg2;
        $httpCode = $arg3 ?? 400;
    }

    http_response_code($httpCode);
    $payload = ['code' => $code];

    if ($message !== null) {
        $payload['message'] = $message;
    }

    echo json_encode(['error' => $payload], JSON_UNESCAPED_UNICODE);
    exit;
}

function cabinet_authorize_user(int $userId): void
{
    global $USER;

    if (! ($USER instanceof CUser)) {
        $USER = new CUser();
    }

    if (! $USER->IsAuthorized()) {
        $USER->Authorize($userId);
    }
}
