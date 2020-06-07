<?php

declare(strict_types = 1);

namespace App\Helpers;


/**
 * Class ShellCmdHelper
 * @package App\Helpers
 */
class ShellCmdHelper
{
// private $wwwRoute = 'cd /var/www';
private $wwwRoute = 'cd /mnt/c/Users/Edvinas/shellOutputTest'; //just for testing


 public function gitPull(string $userFolder,string $projectFolder): string
 {
     return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && git pull 2>&1';
 }

 public function gitClone(string $userFolder,string $projectFolder,string $url): string
 {
    return $this->wwwRoute.'/'.$userFolder.' && git clone '.$url.' '.$projectFolder.' 2>&1';
 }

 public function folder(string $userName,?string $deleteFolder =null): string
 {
    if($deleteFolder === null){
        return $this->wwwRoute.' && mkdir '.$userName;
    }else{
        return $this->wwwRoute.'/'.$userName.' && rm -r '.$deleteFolder;
    }
 }

 public function composerInstall(string $userFolder,string $projectFolder): string
 {
    return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && composer install 2>&1';
 }

 public function dbCreate(string $dbName):string
 {
    //  return 'mysql -upi -ptest -e "create database cmdTest1"';
    return 'mysql -u'.env('DB_USERNAME','root').' -p'.env('DB_PASSWORD','').' -e "create database '.$dbName.'"';

 }

 public function appKeyGenerate(string $userFolder,string $projectFolder):string
 {
     return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan key:generate 2>&1';
 }
 
 public function appStorageLink(string $userFolder,string $projectFolder):string
 {
     //FILESYSTEM_DRIVER=public should be added to .env file before runing this command
     return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan storage:link 2>&1';
 }

 public function dbMigrate(string $userFolder,string $projectFolder):string
 {
     return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan migrate 2>&1';
 }

 public function dumpAutoload(string $userFolder,string $projectFolder):string
 {
     return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan migrate 2>&1';
 }
 public function dbSeed(string $userFolder,string $projectFolder,?string $seedClass = null):string
 {
     if($seedClass && preg_match('/^[a-zA-Z0-9]+$/',$seedClass)){
         return  $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan db:seed --class='.$seedClass.' 2>&1';
     }
     if($seedClass === null){
        return  $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan db:seed 2>&1';
     }
     return '';
 }

 public function customArtisan(string $userFolder,string $projectFolder,string $command):string
 {
     if(!preg_match('/[&|;]+/', $command)){
         return $this->wwwRoute.'/'.$userFolder.'/'.$projectFolder.' && php artisan '.$command.' 2>&1';
     }
     return '';
 }
}
