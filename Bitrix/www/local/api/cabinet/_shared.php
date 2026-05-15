<?php
require_once __DIR__ . '/_config.php';

define('NO_KEEP_STATISTIC',       true);
define('BX_WITH_ON_AFTER_EPILOG', false);
define('NO_AGENT_CHECK',          true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('catalog');

header('Content-Type: application/json; charset=utf-8');

// ---------- Верификация запроса ----------

function cabinet_verify_request(): array
{
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($clientIp, CABINET_ALLOWED_IPS, true)) {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    $timestamp = $_SERVER['HTTP_X_CABINET_TIMESTAMP'] ?? '';
    $signature = $_SERVER['HTTP_X_CABINET_SIGNATURE'] ?? '';

    if ($timestamp === '' || $signature === '') {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    // Защита от replay-атак: окно 60 секунд
    if (abs(time() - (int)$timestamp) > 60) {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    $rawBody  = file_get_contents('php://input');
    $expected = hash_hmac('sha256', $rawBody . $timestamp, CABINET_SERVICE_SECRET);

    if (!hash_equals($expected, $signature)) {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }

    $data = json_decode($rawBody, true);
    if (!is_array($data)) {
        cabinet_error('FORBIDDEN', 'Forbidden', 400);
    }

    return $data;
}

// ---------- Вспомогательные ----------

function cabinet_require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        cabinet_error('FORBIDDEN', 'Forbidden', 403);
    }
}

function cabinet_success(array $data): void
{
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function cabinet_error(string $code, string $message, int $httpCode = 400): void
{
    http_response_code($httpCode);
    echo json_encode(
        ['error' => ['code' => $code, 'message' => $message]],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

// Авторизовать пользователя по ID без проверки пароля.
// Используется в pay/top_up: user_id уже верифицирован Laravel через HMAC.
function cabinet_authorize_user(int $userId): void
{
    global $USER;
    if (!($USER instanceof CUser)) {
        $USER = new CUser();
    }
    if (!$USER->IsAuthorized()) {
        $USER->Authorize($userId);
    }
}
