<?php

declare(strict_types = 1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
/**
*@method static void writeToFile(string $cmd,string $fileName, &$asyncShellOutputStop)
*@method static void asyncShellOutputFileCheck(string $fileName, &$asyncShellOutputStop)
*
**/



class ShellOutput extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ShellOutput';
    }
}