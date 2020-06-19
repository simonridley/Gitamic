<?php

namespace SimonHamp\Gitamic;

use SimonHamp\Gitamic\Contracts\SiteRepository;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js'
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function register()
    {
        app()->singleton(SiteRepository::class, function () {
            return new Repository(base_path());
        });
    }
}
