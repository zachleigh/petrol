<?php

return [
    /*
    | Define database type
    | Options: mysql
    */
    'database' => 'mysql',

    /*
    | Enter connection details for defined database type
    | Sensitive data is pulled from .getenv
    */
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'charset' => 'utf8',
            'host' => getenv('DB_HOST'),
            'database_name' => getenv('DB_DATABASE'),
            'username' => 'hello',
            'password' => getenv('DB_PASSWORD'),
        ],
    ],
];
