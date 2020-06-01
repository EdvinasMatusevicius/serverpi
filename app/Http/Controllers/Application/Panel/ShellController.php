<?php

namespace App\Http\Controllers\Application\Panel;

use App\Facades\ShellCmdBuilder;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShellController extends Controller
{
 
    public function showShell(): View
    {
        $projektas = 'serverpi';
        return view('panel.shellCommandsSection',[
            'project'=> $projektas

        ]);
    }

    public function gitPull(Request $request){
        try {
            $user=auth()->user();
            // $cmd = ShellCmdBuilder::gitPull($user->name,$request->project);
            $cmd = ShellCmdBuilder::gitPull($request->project,'');//for now pulls serverpi whitch is not in user folder
            // $output =shell_exec($cmd);
            $output = $this->writeShellOutput('cd / && ls');
            // shell_exec("nohup ping www.reddit.com > /mnt/c/Users/Edvinas/shellOutputTest/shelltest.txt 2> /mnt/c/Users/Edvinas/shellOutputTest/shellerrors.txt");
            return redirect()->route('showShell',['project'=>$request->project])->with('status','git command finished ');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());
        }
    }

  public function writeShellOutput($cmd){
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("file","/mnt/c/Users/Edvinas/shellOutputTest/shelltest.txt", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", "/mnt/c/Users/Edvinas/shellOutputTest/shellerrors.txt", "w") // stderr is a file to write to
         );
         
         $cwd = '/var/www';
        //  $env = array();
         $process = proc_open($cmd, $descriptorspec, $pipes, $cwd);
         
        //  if (is_resource($process)) {
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
             $return_value = proc_close($process);
         
            //  echo "command returned $return_value\n";
    // }
    }  
}