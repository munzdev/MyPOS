<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],
        
        'DB' => [
            'Typ' => 'mysql',
            'Host' => 'localhost',
            'User' => 'root',
            'Password' => '',
            'Database' => 'mypos',
            'Persistent' => false
        ],
        
        'Auth' => [
            'RememberMe_PrivateKey' => 'MFswDQYJKoZIhvcNAQEBBQADSgAwRwJAfmBSwS0WmfKNW1Dq2N4MJ4gYDApG6lW19QhvDp2g80ajw74D2Xijm4rIuxaJPJ64GazdNWUHuc+1CL5eEkBopwIDAQAB'
        ],
        
        'Organisation' => [
            'Name' => '',
            
            'Invoice' => [
                'Header' => "HEADER TOP LINE\nSECOND LINE\nTHIRD LINE",
                
                'Logo' => [
                    'Use' => false,
                    'Path' => "",
                    'Type' => MyPOS\PRINTER_LOGO_DEFAULT
                ]
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