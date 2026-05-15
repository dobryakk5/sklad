<?php
$arUrlRewrite = array (
  0 => 
  array (
    'CONDITION' => '#^/rental_catalog/([a-zA-Z0-9\\-_]+)/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'SKLAD_CODE=$1&FLOOR_CODE=$2',
    'ID' => '',
    'PATH' => '/rental_catalog/detail.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/rental_catalog/storage/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'SKLAD_CODE=$1',
    'ID' => '',
    'PATH' => '/rental_catalog/detail.v2.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/features_for_personal/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/features_for_personal/detail.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/features_for_business/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/features_for_business/detail.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/rental_catalog/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'SKLAD_CODE=$1',
    'ID' => '',
    'PATH' => '/rental_catalog/detail.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/about/reviews/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'SKLAD_CODE=$1',
    'ID' => '',
    'PATH' => '/about/reviews/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/for_business/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/for_business/detail.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/storage/([a-zA-Z0-9\\-_]+)/(/?)([^/]*)#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/storage/detail.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => 'bitrix:im.router',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/services/furnuture-storage/#',
    'RULE' => '',
    'ID' => 'bitrix:form',
    'PATH' => '/services/furnuture-storage/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/about/useful_for_rental/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/about/useful_for_rental/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/about/voprosi_i_otveti/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/about/voprosi_i_otveti/index.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^/arenda-yachejki/#',
    'RULE' => '',
    'ID' => 'posadochnaya:arenda-yachejki',
    'PATH' => '/posadochnaya/index.php',
    'SORT' => 100,
  ),
  18 => 
  array (
    'CONDITION' => '#^/promotions/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/promotions/index.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/services/index.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^/product/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/product/index.php',
    'SORT' => 100,
  ),
  21 => 
  array (
    'CONDITION' => '#^/urest/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/local/rest/index.php',
    'SORT' => 100,
  ),
  22 => 
  array (
    'CONDITION' => '#^/prest/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/local/rest/pay_rest.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  24 => 
  array (
    'CONDITION' => '#^/blog/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/blog/index.php',
    'SORT' => 100,
  ),
  25 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  26 => 
  array (
    'CONDITION' => '#^/ucrm/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/local/rest/invoice_upd/index.php',
    'SORT' => 100,
  ),
);
