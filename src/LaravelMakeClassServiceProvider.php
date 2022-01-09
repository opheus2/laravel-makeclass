<?php

namespace Orpheus\LaravelMakeClass;

use Illuminate\Support\ServiceProvider;
use Orpheus\LaravelMakeClass\Console\MakeClass;

class LaravelMakeClassServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeClass::class,
            ]);
        }
    }
}
