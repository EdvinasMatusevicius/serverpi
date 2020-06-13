<?php

namespace App\Http\Controllers\Application\Panel;

use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shell\CustomArtisanRunRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShellController extends Controller
{
 
    public function showShell(Request $request): View
    {
        return view('panel.shellCommandsSection',[
            'project'=> $request->project
        ]);
    }

    public function git_pull(Request $request){
        return $this->tryCatchBlock($request->project,'gitPull');
        
    }
    public function composer_install(Request $request){
        return $this->tryCatchBlock($request->project,'composerInstall');

    }
    public function app_key_generate(Request $request){
        return $this->tryCatchBlock($request->project,'appKeyGenerate');

    }
    public function app_storage_link(Request $request){
        return $this->tryCatchBlock($request->project,'appStorageLink');

    }
    public function db_migrate(Request $request){
        return $this->tryCatchBlock($request->project,'dbMigrate');

    }
    public function dump_autoload(Request $request){
        return $this->tryCatchBlock($request->project,'dumpAutoload');

    }
    public function db_seed(Request $request){
        try {
            $user=auth()->user();
            $cmd = ShellCmdBuilder::dbSeed($user->name,$request->project,$request->seedClass);
            // $cmd = ShellCmdBuilder::dbSeed($request->project,'');//for now uses serverpi whitch is not in user folder\
            $stream = ShellOutput::writeToFile($cmd);
           if($stream === 0){
            return redirect()->route('showShell',['project'=>$request->project])->with('status','database seeding finished');
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());
        }
    }
    public function custom_artisan(CustomArtisanRunRequest $request){

        try {
            $user=auth()->user();
            $cmd = ShellCmdBuilder::customArtisan($user->name,$request->project,$request->artisanCmd);
            $stream = ShellOutput::writeToFile($cmd);
            if($stream === 0){
                return redirect()->route('showShell',['project'=>$request->project])->with('status','artisan command finished');
            }
            throw new Exception('error '.$stream.' accured');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());
        }
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
            $cmd = ShellCmdBuilder::$command($user->name,$project);
            // $cmd = ShellCmdBuilder::$command($project,'');//for now uses serverpi whitch is not in user folder\
            // dd($cmd,$cmdNameArr[$command]);
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