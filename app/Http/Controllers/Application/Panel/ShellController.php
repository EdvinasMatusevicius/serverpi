<?php

namespace App\Http\Controllers\Application\Panel;

use App\Facades\NginxConfigBuilder;
use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shell\CustomArtisanRunRequest;
use App\Http\Requests\Shell\DatabaseCreateRequest;
use App\Http\Requests\Shell\DatabaseCustomQueryRequest;
use App\Repositories\ApplicationRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShellController extends Controller
{
    private $userRepository;
    private $applicationRepository;

    public function __construct(UserRepository $userRepository, ApplicationRepository $applicationRepository)
    {
        $this->userRepository = $userRepository;
        $this->applicationRepository = $applicationRepository;
    }
 
    public function showShell(Request $request): View
    {
        //ar yra sukurta db ir db useris  pasiusti info 
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
    public function db_create(DatabaseCreateRequest $request){

        return $this->dbTryCatchBlock($request->project,'dbCreate',$request->password);
        // dd($this->userRepository->userHasRepositoryUser());
        // dd($this->userRepository->userHasRepositoryUser());
    }
    public function db_custom_query(DatabaseCustomQueryRequest $request){
        return $this->dbTryCatchBlock($request->project,'dbCustomQuery',$request->password,$request->customquery);
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
            $cmd =  NginxConfigBuilder::phpApplicationCmd($user->name,$request->project,$request->path);
            $stream = ShellOutput::writeToFile($cmd,$user->name,);
            if($stream === 0){
             return redirect()->route('showShell',['project'=>$request->project])->with('status','sukure configa nginx');
         }
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());

        }
    }

    private function dbTryCatchBlock (string $project,string $command,string $password,?string $customQuery =null){
        $cmdNameArr = [
            'dbAndUserCreate'=>'databbase and user created',
            'dbAndPrivilegeCreate'=> 'databbase created',
            'dbCustomQuery'=>'database command executed',
        ];
        $databaseName = str_replace("-","_",$project);
        if($command === 'dbCreate' && $this->userRepository->userHasRepositoryUser())
            { $command = 'dbAndPrivilegeCreate';
        }else if($command === 'dbCreate'){
            $command = 'dbAndUserCreate';
        }

        try {
            $user=auth()->user();
            $cmd = ShellCmdBuilder::$command($user->name,$databaseName,$password,$customQuery);

            $stream = ShellOutput::writeToFile($cmd,$user->name,);

           if($stream === 0){ 
                    if($command === 'dbAndUserCreate'){
                        $this->userRepository->updateRepositoryUser();//TEST IN RASPBERY IF DB SHELL COMMANDS WORK
                    }
                    if($command === 'dbAndUserCreate' || $command === 'dbAndPrivilegeCreate') {
                        $this->applicationRepository->applicationAddDatabase($project);
                    }
            return redirect()->route('showShell',['project'=>$project])->with('status',$cmdNameArr[$command]);
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$project])->with('danger','something went wrong '.$exception->getMessage());
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