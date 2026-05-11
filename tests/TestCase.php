<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            PreventRequestForgery::class,
            ValidateCsrfToken::class,
            VerifyCsrfToken::class,
        ]);

        config()->set('session.driver', 'array');
        config()->set('cache.default', 'array');
        config()->set('cache.limiter', 'array');
        config()->set('queue.default', 'sync');

        $this->app['cache']->setDefaultDriver('array');
    }
}
