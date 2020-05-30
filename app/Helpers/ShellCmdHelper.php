<?php

declare(strict_types = 1);

namespace App\Helpers;

use Exception;

/**
 * Class ShellCmdHelper
 * @package App\Helpers
 */
class ShellCmdHelper
{
private $wwwRoute = 'cd /var/www';
private $usableGitComands = ['pull','status'];

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
 
}
