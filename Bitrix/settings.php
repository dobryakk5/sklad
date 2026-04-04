<?php
return array (
  'session' => array (
  'value' => 
  array (
    'mode' => 'default',
  ),
  'readonly' => true,
),
  'utf_mode' => 
  array (
    'value' => true,
    'readonly' => true,
  ),
  'cache_flags' => 
  array (
    'value' => 
    array (
      'config_options' => 3600.0,
      'site_domain' => 3600.0,
    ),
    'readonly' => false,
  ),
  'cookies' => 
  array (
    'value' => 
    array (
      'secure' => false,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' => 
  array (
    'value' => 
    array (
      'debug' => true,
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
        'log' => array (
            'settings' => array (
                'file' => 'bitrix/error2.log',
                'log_size' => 1000000000,
            ),
        ),
    ),
    'readonly' => false,
  ),
  'connections' => 
  array (
    'value' => 
    array (
      'default' => 
      array (
        'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
        'host' => '213.171.9.201',
        'database' => 'sitemanager',
        'login' => 'bitrix0',
        'password' => '%zO?vSQje!us&mQ]qWY8',
        'options' => 2.0,
      ),
    ),
    'readonly' => true,
  ),
  'crypto' => 
  array (
    'value' => 
    array (
      'crypto_key' => 'fd66de3ed70272f85f72d57c8110009e',
    ),
    'readonly' => true,
  ),
   /* 'analytics_counter' => array (
        'value' =>
            array (
                'enabled' => false,
            ),
    )*/
);
