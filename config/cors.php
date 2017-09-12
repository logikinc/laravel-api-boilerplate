<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => true,
    'allowedOrigins' => array('*'),
    'allowedHeaders' => array('*'),
    'allowedMethods' => ['HEAD', 'GET', 'PUT', 'POST', 'OPTIONS'],
    'exposedHeaders' => array('*'),
    'maxAge' => 0,
    'hosts' => array('*'),
];

