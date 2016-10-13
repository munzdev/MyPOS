<?php
return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'mysql',
                    'dsn' => 'mysql:host=localhost;port=3306;dbname=mypos',
                    'user' => 'root',
                    'password' => '',
                    'settings' => [
                        'charset' => 'utf8mb4'
                    ]
                ]
            ]
        ]
    ]
];
