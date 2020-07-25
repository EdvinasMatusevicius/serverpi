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
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("file","/var/www/sh/{$fileName}/shell.txt", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", "/var/www/sh/{$fileName}/shellerrors.txt", "w") // stderr is a file to write to
            // 1 => array("file","/mnt/c/Users/Edvinas/shellOutputTest/shelltest.txt", "w"),  // stdout is a pipe that the child will write to
            // 2 => array("file", "/mnt/c/Users/Edvinas/shellOutputTest/shellerrors.txt", "w") // stderr is a file to write to
         );
         
         $cwd = base_path();
        //  $env = array();
         $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
         
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
         
             // proc_close in order to avoid a deadlock
            $exitCode = proc_close($process);
            return $exitCode;
    }
    }  
}





//  SCAFOLD FOR STREAM DATA

// public function runAndStreamCmd(string $cmd):int
// { //FILE NAME NULL WHILE TESTING TO SHELLTEST.TXT
//     //PI route /var/www/users/-$userName-/sh/-$fileName.txt-
//     ob_implicit_flush(1);
//     ob_end_flush();
//     $descriptorspec = array(
//         0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//         1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//         2 => array("pipe", "w") // stderr is a file to write to
//      );
     
//      $cwd = base_path();
//      $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
//      if (is_resource($process)) {
//         while ($s = fgets($pipes[1],1024)) {
//             print $s;
//         }

//          fclose($pipes[1]);
      
//         $exitCode = proc_close($process);
//         return $exitCode;
// }
// } 
