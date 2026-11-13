<?php

namespace Config;

use App\Filters\AuthFilter;
use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

/**
 * Filters — auth mirrors CI3 constructor checks on Products and Categories only.
 * CI3: csrf_protection = FALSE (no global CSRF filter).
 */
class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => AuthFilter::class,
    ];

    public array $required = [
        'before' => [
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [],
        'after'  => [],
    ];

    public array $methods = [];

    /**
     * CI3 session check in Products::__construct and Categories::__construct.
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'products',
                'products/*',
                'categories',
                'categories/*',
            ],
        ],
    ];
}
