<?php

namespace App\Providers;

use App\Helpers\ShellCmdHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindFacades();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    private function bindFacades(): void
    {
        $this->app->bind('ShellCmdBuilder',function() {
            return new ShellCmdHelper();
        });
    }
}
