<?php

declare(strict_types=1);

return [
    'canonical_host' => 'https://www.prooftagcatis.com',
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
        'centre' => ['fr' => '/centres-de-controle', 'en' => '/inspection-centres'],
        'post' => ['fr' => '/actualites', 'en' => '/news'],
        'resource' => ['fr' => '/ressources', 'en' => '/resources'],
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
