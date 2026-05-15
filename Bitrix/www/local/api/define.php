<?php
    $domain = COption::GetOptionString("main", "server_name", $GLOBALS["SERVER_NAME"]);
    define('DEFAULT_SITE_ID', 's1');
    define('SITE_DOMAIN', $domain);
    define('SITE_PROTOCOL', 'http://');
    define('SITE_FULL_DOMAIN', SITE_PROTOCOL . $domain);