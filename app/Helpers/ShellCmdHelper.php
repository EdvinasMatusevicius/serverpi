<?php

declare(strict_types = 1);

namespace App\Helpers;


/**
 * Class ShellCmdHelper
 * @package App\Helpers
 */
class ShellCmdHelper
{
private $wwwRoute = 'cd /var/www';
// private $wwwRoute = 'cd /mnt/c/Users/Edvinas/shellOutputTest'; //just for testing
private function routeToProject(string $userFolder,string $projectFolder){
    return $this->wwwRoute.'/"'.$userFolder.'"/"'.$projectFolder;
}
private function connectToDb(){
    return 'mysql -u'.env('DB_USERNAME','root').' -p'.env('DB_PASSWORD','');
}

 public function gitPull(string $userFolder,string $projectFolder): string
 {
     return $this->routeToProject($userFolder,$projectFolder).'" && git pull 2>&1';
 }

 public function gitClone(string $userFolder,string $projectFolder,string $url): string
 {
    return $this->wwwRoute.'/"'.$userFolder.'" && git clone '.$url.' "'.$projectFolder.'" 2>&1';
 }

 public function userFolder(string $userName,?string $deleteFolder =null): string
 {
    if($deleteFolder === null){
        return $this->wwwRoute.' && mkdir "'.$userName.'"';
    }else{
        return $this->wwwRoute.'/"'.$userName.'" && rm -r "'.$deleteFolder.'"';
    }
 }
 public function shOutputFolder(string $userName): string
 {
        return $this->wwwRoute.'/"'.$userName.'" && mkdir sh';
 }
 public function composerInstall(string $userFolder,string $projectFolder): string
 {
    return $this->routeToProject($userFolder,$projectFolder).'" && composer install 2>&1';
 }
 public function npmInstall(string $userFolder,string $projectFolder): string
 {
    return $this->routeToProject($userFolder,$projectFolder).'" && npm install 2>&1';
 }
 //TO DO: yarn
 public function copyEnvExample(string $userFolder,string $projectFolder): string
 {
    return $this->routeToProject($userFolder,$projectFolder).'" && cp .env.example .env 2>&1';
 }
 public function createEnvFile(string $userFolder,string $projectFolder): string
 {
    return $this->routeToProject($userFolder,$projectFolder).'" && touch .env 2>&1';
 }
 public function getEnvFileValues(string $userFolder,string $projectFolder): string
 {
    return $this->routeToProject($userFolder,$projectFolder).'" && cat .env 2>&1';
 }
 public function writeToEnvFile(string $userFolder,string $projectFolder,string $values): string
 {
    return $this->routeToProject($userFolder,$projectFolder).'" && printf "'.$values.'" > .env 2>&1';
 }
 public function dbAndUserCreate(string $userName, string $dbName, string $password){
    return $this->connectToDb().' -e "create database '.$dbName.'; CREATE USER \''.$userName.'\'@\'localhost\' IDENTIFIED BY \''.$password.'\'; GRANT ALL ON '.$dbName.'.* TO \''.$userName.'\'@\'localhost\';"';
 }
 public function dbAndPrivilegeCreate(string $userName,string $dbName):string
 {
    return $this->connectToDb().' -e "create database '.$dbName.'; GRANT ALL ON '.$dbName.'.* TO \''.$userName.'\'@\'localhost\';"';
 }
 public function dbCustomQuery(string $userName, string $dbName, string $password,string $customQuery){
    return 'mysql -u'.$userName.' -p'.$password.' -e "USE '.$dbName.'; '.$customQuery.'"';
 }

 public function appKeyGenerate(string $userFolder,string $projectFolder):string
 {
     return $this->routeToProject($userFolder,$projectFolder).'" && php artisan key:generate 2>&1';
 }
 public function configCashe(string $userFolder,string $projectFolder):string
 {
     return $this->routeToProject($userFolder,$projectFolder).'" && php artisan config:cache 2>&1';
 }
 
 public function appStorageLink(string $userFolder,string $projectFolder):string
 {
     //FILESYSTEM_DRIVER=public should be added to .env file before runing this command
     return $this->routeToProject($userFolder,$projectFolder).'" && php artisan storage:link 2>&1';
 }

 public function dbMigrate(string $userFolder,string $projectFolder):string
 {
     return $this->routeToProject($userFolder,$projectFolder).'" && php artisan migrate 2>&1';
 }

 public function dumpAutoload(string $userFolder,string $projectFolder):string
 {
     return $this->routeToProject($userFolder,$projectFolder).'" && php artisan dump-autoload 2>&1';
 }
 public function dbSeed(string $userFolder,string $projectFolder,?string $seedClass = null):string
 {
     if($seedClass && preg_match('/^[a-zA-Z0-9]+$/',$seedClass)){
         return  $this->routeToProject($userFolder,$projectFolder).'" && php artisan db:seed --class='.$seedClass.' 2>&1';
     }
     if($seedClass === null){
        return  $this->routeToProject($userFolder,$projectFolder).'" && php artisan db:seed 2>&1';
     }
     return '';
 }
 //need to implement
 public function customArtisan(string $userFolder,string $projectFolder,string $command):string
 {
     if(!preg_match('/[&|;]+/', $command)){
         return $this->routeToProject($userFolder,$projectFolder).'" && php artisan '.$command.' 2>&1';
     }
     return '';
 }

}
