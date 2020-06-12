<?php

declare(strict_types = 1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
/**
*@method static string gitPull(string $userFolder,string $projectFolder)
*@method static string gitClone(string $userFolder,string $projectFolder,string $url)
*@method static string userFolder(string $userName,?string $deleteFolder =null)
*@method static string shOutputFolder(string $userName)
*@method static string composerInstall(string $userFolder,string $projectFolder)
*@method static string dbCreate(string $dbName)
*@method static string appKeyGenerate(string $userFolder,string $projectFolder)
*@method static string appStorageLink(string $userFolder,string $projectFolder)
*@method static string dbMigrate(string $userFolder,string $projectFolder)
*@method static string dumpAutoload(string $userFolder,string $projectFolder)
*@method static string dbSeed(string $userFolder,string $projectFolder,?string $seedClass = null)
*@method static string customArtisan(string $userFolder,string $projectFolder,string $command)
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