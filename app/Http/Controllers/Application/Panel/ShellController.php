<?php

namespace App\Http\Controllers\Application\Panel;

use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
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
        return $this->tryCatchBlock($request->project,'gitPull');
        
    }
    public function composerInstall(Request $request){
        return $this->tryCatchBlock($request->project,'composerInstall');

    }
    public function appKeyGenerate(Request $request){
        return $this->tryCatchBlock($request->project,'appKeyGenerate');

    }
    public function appStorageLink(Request $request){
        return $this->tryCatchBlock($request->project,'appStorageLink');

    }
    public function dbMigrate(Request $request){
        return $this->tryCatchBlock($request->project,'dbMigrate');

    }
    public function dumpAutoload(Request $request){
        return $this->tryCatchBlock($request->project,'dumpAutoload');

    }
  private function tryCatchBlock (string $project,string $command){
        $cmdNameArr = [
            'gitPull'=>'git command finished ',
            'composerInstall'=>'composer install command finished',
            'appKeyGenerate'=>'app key generated',
            'appStorageLink'=>'storage linked',
            'dbMigrate'=>'database finished migrating',
            'dumpAutoload'=>'composer dump-autoload command finished',
        ];

        try {
            $user=auth()->user();
            // $cmd = ShellCmdBuilder::$command($user->name,$project);
            $cmd = ShellCmdBuilder::$command($project,'');//for now uses serverpi whitch is not in user folder\
            dd($cmd,$cmdNameArr[$command]);
            $stream = ShellOutput::writeToFile($cmd);
           if($stream === 0){
            return redirect()->route('showShell',['project'=>$project])->with('status',$cmdNameArr[$command]);
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$project])->with('danger','something went wrong '.$exception->getMessage());
        }
  }
}