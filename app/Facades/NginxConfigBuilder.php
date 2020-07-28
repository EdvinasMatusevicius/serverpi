<?php

declare(strict_types = 1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
/**
*@method static string staticApplicationCmd(string $user,string $project, string $rootCustom)
*@method static string phpApplicationCmd($user,$project,$rootCustom)
*@method static string vueBuiltApplicationCmd(string $user,string $project, string $rootCustom)
*
**/



class NginxConfigBuilder extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'NginxConfigBuilder';
    }
}