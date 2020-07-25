<?php

declare(strict_types = 1);

namespace App\Helpers;

use Exception;
use React\EventLoop\Factory;
use React\EventLoop\TimerInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ShellOutputHelper
 * @package App\Helpers
 */
class ShellOutputHelper
{
    
    public function runAndStreamCmd(string $cmd,$request)
    { //FILE NAME NULL WHILE TESTING TO SHELLTEST.TXT
        //PI route /var/www/users/-$userName-/sh/-$fileName.txt-
        try{
            
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
            
            // $cwd = base_path();
            
            // $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
            // if (is_resource($process)) {
                // $response = new StreamedResponse(function() use ($request,$pipes) {
                    $response = new StreamedResponse(function() use ($request) {
                    
                    // while ($s = fgets($pipes[1],1024)) {
                        $s =0;
                        while ($s <100) {
                        // print $s;
                        $s = $s + 1;
                        echo 'data: ' . json_encode($s) . "\n\n";
                        ob_flush();
                        flush();
                        usleep(200000);
                    }
                });
                $response->headers->set('Content-Type', 'text/event-stream');
                $response->headers->set('X-Accel-Buffering', 'no');
                $response->headers->set('Cach-Control', 'no-cache');
                response()->send();
                
                
                //-------------------------------
                //          fwrite($pipes[0],"");
                // fclose($pipes[1]);
                //  fclose($pipes[0]);
                
                // $exitCode = proc_close($process);
                // return $exitCode;
                return $response;
            // }
        }catch(Exception $exception){
            shell_exec(`cd /var/www && ${exception} > sse.txt`);
            return $exception;
        } 

    }
        
}
