<?php

declare(strict_types=1);

return [
    'canonical_host' => 'https://www.prooftagcatis.com',
    /*
     | Production www is indexable. Staging / local stay noindex unless overridden.
     */
    'robots' => env('CMS_ROBOTS', env('APP_ENV') === 'production' ? 'index,follow' : 'noindex,nofollow'),
    'contact_throttle_max' => 5,
    'contact_throttle_decay_seconds' => 60,
    'honeypot_field' => 'website',
    'cache_ttl' => 3600,
    'allowed_redirect_hosts' => [
        'www.prooftagcatis.com',
        'site.prooftagcatis.com',
        'net.prooftagcatis.com',
        'prooftagcatis.com',
    ],
    'path_prefixes' => [
        'product' => ['fr' => '/produits', 'en' => '/products'],
        'expertise' => ['fr' => '/expertises', 'en' => '/expertise'],
        'equipment' => ['fr' => '/controle-technique/equipements', 'en' => '/technical-inspection/equipment'],
        'centre' => ['fr' => '/centres-de-controle', 'en' => '/inspection-centres'],
        'post' => ['fr' => '/actualites', 'en' => '/news'],
        'resource' => ['fr' => '/ressources', 'en' => '/resources'],
        'career' => ['fr' => '/a-propos/carrieres', 'en' => '/about/careers'],
    ],
    'media' => [
        'max_bytes' => 8 * 1024 * 1024,
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/svg+xml',
            'application/pdf',
        ],
        'image_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/svg+xml',
        ],
    ],
];
