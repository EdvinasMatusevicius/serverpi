<?php

declare(strict_types = 1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
/**
*@method static string gitPull(string $userFolder,string $projectFolder)
*@method static string gitClone(string $userFolder,string $projectFolder,string $url)
*@method static string folder(string $userName,?string $deleteFolder =null)
*
**/



class ShellCmdBuilder extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ShellCmdBuilder';
    }
}