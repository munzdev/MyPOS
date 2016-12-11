<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => API\DEBUG,

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

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
        ],

        'Auth' => [
            'RememberMe_PrivateKey' => 'MFswDQYJKoZIhvcNAQEBBQADSgAwRwJAfmBSwS0WmfKNW1Dq2N4MJ4gYDApG6lW19QhvDp2g80ajw74D2Xijm4rIuxaJPJ64GazdNWUHuc+1CL5eEkBopwIDAQAB'
        ],

        'Organisation' => [
            'Name' => '',
            'Adress' => '',
            'Adress2' => '',
            'ZIP' => '',
            'City' => '',
            'TaxIdentificationNr' => '',
            'Telephon' => '',
            'Fax' => '',
            'Email' => '',
            'Bank' => '',
            'IBAN' => '',
            'BIC' => '',
        ],

        'Invoice' => [
            'Header' => "HEADER TOP LINE\nSECOND LINE\nTHIRD LINE",

            'Logo' => [
                'Use' => false,
                'Path' => "",
                'Type' => API\PRINTER_LOGO_DEFAULT
            ]
        ],

        'App' => [
            'Domain' => 'mypos.localhost',

            'Distribution' => [
                'AmountOrdersToPreShow' => 2,
                'OnStandbyAssistOtherDistributionPlaces' => true,
                'AmountDisplayedInTodoList' => 5,
                'OrderProgressTimeRangeMinutes' => 5
            ]
        ]
    ],
];