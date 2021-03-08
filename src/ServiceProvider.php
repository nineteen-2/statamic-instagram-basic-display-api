<?php

namespace NineteenSquared\Instagram;

use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $tags = [
        \NineteenSquared\Instagram\Tags\Instagram::class,
    ];

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'nineteen-ig');
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'statamic.instagram');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('statamic/instagram.php'),
            ], 'instagram-config');
        }

        Nav::extend(function ($nav) {
            $nav->create('Instagram')
                ->icon('assets')
                ->section('Tools')
                ->route('nineteen-ig.index')
                ->can('setup Instagram');
        });

        $this->app->booted(function () {
            Permission::group('instagram', 'Instagram', function () {
                Permission::register('setup Instagram')
                          ->label('setup instagram');
            });
        });
    }
}
