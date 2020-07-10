<?php

declare(strict_types = 1);

namespace App\Helpers;

use React\EventLoop\Factory;
use React\EventLoop\TimerInterface;

/**
 * Class ShellOutputHelper
 * @package App\Helpers
 */
class ShellOutputHelper
{
    public function writeToFile(string $cmd,string $fileName, &$asyncShellOutputStop):int
    { //FILE NAME NULL WHILE TESTING TO SHELLTEST.TXT
        //PI route /var/www/users/-$userName-/sh/-$fileName.txt-
        ob_implicit_flush(1);
        ob_end_flush();
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("pipe", "w") // stderr is a file to write to




            // 1 => array("file","/var/www/sh/{$fileName}/shell.txt", "w"),  // stdout is a pipe that the child will write to
            // 2 => array("file", "/var/www/sh/{$fileName}/shellerrors.txt", "w") // stderr is a file to write to
            // 1 => array("file","/mnt/c/Users/Edvinas/shellOutputTest/shelltest.txt", "w"),  // stdout is a pipe that the child will write to
            // 2 => array("file", "/mnt/c/Users/Edvinas/shellOutputTest/shellerrors.txt", "w") // stderr is a file to write to
         );
         
         $cwd = base_path();
         $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
         if (is_resource($process)) {
            while ($s = fgets($pipes[1],1024)) {
                print $s;
            }



            //-------------------------------
    //          fwrite($pipes[0],"");
             fclose($pipes[0]);
          
            $exitCode = proc_close($process);
            return $exitCode;
    }
    } 



    //PROBABLY USELESS   IF NOT NEEDED DELETE REACT-PHP DEPENDENCY
    //  public function asyncShellOutputFileCheck(string $fileName, &$asyncShellOutputStop){
    //     $loop = Factory::create();
    //     $asyncLoop = $loop->addPeriodicTimer(1, function () use ($loop,$fileName,&$asyncLoop,&$asyncShellOutputStop) {
    //         if($asyncShellOutputStop === true){
    //             $loop->cancelTimer($asyncLoop);
    //         }
    //         dump(file_get_contents("/var/www/sh/{$fileName}/shell.txt"),$asyncShellOutputStop);
    //     });
    // $loop->run();
    //  }
}
