<?php

return [
    /*
    | Define database type
    | Options: mysql
    */
    'default' => 'mysql',

    /*
    | Enter connection details for defined database type
    | Sensitive data is pulled from .getenv
    */
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'charset' => 'utf8',
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_DATABASE'),
            'username' => 'hello',
            'password' => getenv('DB_PASSWORD'),
        ],
    ],
];
