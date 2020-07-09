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
    public function writeToFile(string $cmd,?string $fileName=null):int
    { //FILE NAME NULL WHILE TESTING TO SHELLTEST.TXT
        //PI route /var/www/users/-$userName-/sh/-$fileName.txt-
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("file","/var/www/sh/{$fileName}/shell.txt", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", "/var/www/sh/{$fileName}/shellerrors.txt", "w") // stderr is a file to write to
            // 1 => array("file","/mnt/c/Users/Edvinas/shellOutputTest/shelltest.txt", "w"),  // stdout is a pipe that the child will write to
            // 2 => array("file", "/mnt/c/Users/Edvinas/shellOutputTest/shellerrors.txt", "w") // stderr is a file to write to
         );
         
         $cwd = base_path();
         $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
         $exitCode = NULL;
         $this->asyncShellOutputFileCheck($fileName, $exitCode);
         if (is_resource($process)) {

           
             // $pipes now looks like this:
             // 0 => writeable handle connected to child stdin
             // 1 => readable handle connected to child stdout
             // Any error output will be appended to /tmp/error-output.txt
            //  echo $pipes[0];
            //  dd($pipes[0]);
             fwrite($pipes[0],"");
             fclose($pipes[0]);
            //  fclose($pipes[1]);
            //  fclose($pipes[2]);
         
             // It is important that you close any pipes before calling
             // proc_close in order to avoid a deadlock
            $exitCode = proc_close($process);
            dump($exitCode."this is after proc close");
            return $exitCode;
            //  echo "command returned $return_value\n";
    }
    } 
     private function asyncShellOutputFileCheck(string $fileName, &$exitCode){
        $loop = Factory::create();
        $timeris = 10 + (int)time();
        $asyncLoop = $loop->addPeriodicTimer(1, function () use ($loop,$fileName,&$asyncLoop,&$exitCode,&$timeris) {
            if($exitCode !== NULL){
                $loop->cancelTimer($asyncLoop);
            }
            dump(file_get_contents("/var/www/sh/{$fileName}/shell.txt"),$exitCode ."this is exit code in loop");
        });
    $loop->run();
     }
}
