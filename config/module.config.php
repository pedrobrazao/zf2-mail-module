<?php

namespace PhowerMailModule;

return [
    'service_manager' => [
        'factories' => [
            'PhowerMailService' => Service\Mail\MailServiceFactory::class,
        ],
    ],
];
