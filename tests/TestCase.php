<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Waterhole\Providers\WaterholeServiceProvider;
use Waterhole\StopForumSpam\StopForumSpamServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            SocialiteServiceProvider::class,
            WaterholeServiceProvider::class,
            StopForumSpamServiceProvider::class,
        ];
    }
}
