<?php

namespace App\Http\Controllers\Application\Panel;

use App\Facades\NginxConfigBuilder;
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
    public function npm_install(Request $request){
        return $this->tryCatchBlock($request->project,'npmInstall');

    }
    public function copy_env_example(Request $request){
        return $this->tryCatchBlock($request->project,'copyEnvExample');

    }
    public function create_env_file(Request $request){
        return $this->tryCatchBlock($request->project,'createEnvFile');

    }
    public function get_env_values(Request $request){
    try{
        $user=auth()->user();
        $cmd = ShellCmdBuilder::getEnvFileValues($user->name,$request->project);
        $values = shell_exec($cmd);
        return view('panel/shellCommandsSection',['project'=>$request->project,'valuess'=>$values]);
    } catch (Exception $exception) {
        return redirect()->route('home',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());
    }
    }
    public function write_to_env_file(Request $request){
        return $this->tryCatchBlock($request->project,'writeToEnvFile',$request->envVars);

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
        return $this->tryCatchBlock($request->project,'dbSeed',$request->seedClass);

    }
    public function custom_artisan(CustomArtisanRunRequest $request){
        return $this->tryCatchBlock($request->project,'customArtisan',$request->artisanCmd);

    }
    public function nginx_config(Request $request){
        try {
            $user=auth()->user();
            //from db check which language
            $cmd =  NginxConfigBuilder::phpApplicationCmd($user,$request->project,$request->path);
            $stream = ShellOutput::writeToFile($cmd,$user->name,);
            if($stream === 0){
             return redirect()->route('showShell',['project'=>$request->project])->with('status','sukure configa nginx');
         }
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());

        }
    }


    
  private function tryCatchBlock (string $project,string $command,?string $dynamicCmdValAfterCdRoute =null){
        $cmdNameArr = [
            'gitPull'=>'git command finished ',
            'composerInstall'=>'composer install command finished',
            'npmInstall'=>'npm install command finished',
            'copyEnvExample'=>'.env.example copied with .env name',
            'createEnvFile'=>'.empty .env file created',
            'writeToEnvFile'=>'values saved to .env file',
            'appKeyGenerate'=>'app key generated',
            'appStorageLink'=>'storage linked',
            'dbMigrate'=>'database finished migrating',
            'dumpAutoload'=>'composer dump-autoload command finished',
            'dbSeed'=>'database seed comand finished',
            'customArtisan'=>'artisan comand finished',
        ];

        try {
            $user=auth()->user();
            $cmd = ShellCmdBuilder::$command($user->name,$project,$dynamicCmdValAfterCdRoute);
            $stream = ShellOutput::writeToFile($cmd,$user->name,);
           if($stream === 0){
            return redirect()->route('showShell',['project'=>$project])->with('status',$cmdNameArr[$command]);
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$project])->with('danger','something went wrong '.$exception->getMessage());
        }
  }
}