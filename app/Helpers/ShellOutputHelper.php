<?php

declare(strict_types = 1);

namespace App\Helpers;

/**
 * Class ShellOutputHelper
 * @package App\Helpers
 */
class ShellOutputHelper
{
    public function writeToFile(string $cmd,?string $fileName=null):void  //void unless will need exit status
    { //FILE NAME NULL WHILE TESTING TO SHELLTEST.TXT
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("file","/mnt/c/Users/Edvinas/shellOutputTest/shelltest.txt", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", "/mnt/c/Users/Edvinas/shellOutputTest/shellerrors.txt", "w") // stderr is a file to write to
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
         
             // It is important that you close any pipes before calling
             // proc_close in order to avoid a deadlock
             proc_close($process);
         
            //  echo "command returned $return_value\n";
    }
    }  
}
