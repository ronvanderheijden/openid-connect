<?php

declare(strict_types=1);

use OpenIDConnect\Repositories\IdentityRepository;
use OpenIDConnect\Repositories\ScopeRepository;

return [
    'passport' => [
        'tokens_can' => [
            'openid' => 'Enable OpenID Connect',
            'profile' => 'Information about your profile',
            'email' => 'Information about your email address',
            'phone' => 'Information about your phone numbers',
            'address' => 'Information about your address',
            // 'login' => 'See your login information',
        ],
    ],

    'custom_claim_sets' => [
        // 'login' => [
        //     'last-login',
        // ],
    ],

    'repositories' => [
        'identity' => IdentityRepository::class,
        'scope' => ScopeRepository::class,
    ],
];
