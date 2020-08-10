<?php

namespace App\Providers;

use App\Helpers\ShellCmdHelper;
use App\Helpers\ShellOutputHelper;
use App\Helpers\NginxConfigHelper;
use App\Helpers\UserHelper;
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
        $this->app->bind('ShellOutput',function(){
            return new ShellOutputHelper();
        });
        $this->app->bind('NginxConfigBuilder',function(){
            return new NginxConfigHelper();
        });
        $this->app->bind('UserFacade',function(){
            return new UserHelper();
        });
    }
}
