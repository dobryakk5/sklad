<?php
// Не должен быть доступен напрямую — закрыт nginx-правилом:
// location ~ /local/api/cabinet/_.*\.php$ { deny all; return 403; }

define('CABINET_SERVICE_SECRET', '9ca3656a71d830f12b90cd55a6452b287b6147bcedbfdfe322072037a7955234');

define('CABINET_ALLOWED_IPS', [
    '213.171.9.201', '46.17.106.45'
]);

define('CONTRACTS_IBLOCK_ID',        52);
define('INVOICES_IBLOCK_ID',         53);
define('PAYMENT_METHODS_IBLOCK_ID',  69);
define('BALANCE_PRODUCT_ID',         9819);
define('PAY_SYSTEM_ID',              7);

// Статус активного договора — уточнить реальный ID:
// SELECT ID, NAME FROM b_iblock_element WHERE IBLOCK_ID=1 (статусы)
define('CONTRACT_STATUS_ACTIVE_ID', 1);

// Статусы счёта, которые можно оплатить
// Уточнить INVOICE_STATUS_NOTPAID_ID реальным значением из БД
define('INVOICE_PAYABLE_STATUSES', [
    420, // INVOICE_STATUS_NOTPAID_ID — уточнить
    421, // в обработке
    356, // частично оплачен
]);

define('ORDER_DEAL_CATEGORY_ID', 3);
define('PAYMENT_URL_BASE', 'https://alfasklad.ru/order/?ORDER_ID=');
