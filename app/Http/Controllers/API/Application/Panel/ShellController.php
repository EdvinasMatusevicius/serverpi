<?php

namespace App\Http\Controllers\API\Application\Panel;

use App\Facades\NginxConfigBuilder;
use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Shell\CustomArtisanRunRequest;
use App\Http\Requests\API\Shell\DatabaseCreateRequest;
use App\Http\Requests\API\Shell\DatabaseCustomQueryRequest;
use App\Http\Responses\ApiResponse;
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
        return $this->getValueTryBlock($request->project,'getEnvFileValues','values');
    }
    public function write_to_env_file(Request $request){
        return $this->tryCatchBlock($request->project,'writeToEnvFile',$request->envVars);

    }
    public function app_key_generate(Request $request){
        return $this->tryCatchBlock($request->project,'appKeyGenerate');

    }  

    public function config_cache(Request $request){
        return $this->tryCatchBlock($request->project,'configCashe');

    }
    public function app_storage_link(Request $request){
        return $this->tryCatchBlock($request->project,'appStorageLink');

    }
    public function db_create(DatabaseCreateRequest $request){

        return $this->dbTryCatchBlock($request->project,'dbCreate',$request->password);
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
      if($this->applicationRepository->applicationIsDeployed($request->project) !== '1'){  
          try {
            $user=auth()->user();
            $language = $this->applicationRepository->applicationLanguage($request->project);
            switch ($language) {
                case '1':
                    $fnName = "phpApplicationCmd";
                    break;
                case '2':
                    $fnName = "vueBuiltApplicationCmd";
                    break;
                case '3':
                    $fnName = "staticApplicationCmd";
                    break;
                default:
                    throw new Exception("Invalid project language", 1);
                    break;
            }
            $cmd =  NginxConfigBuilder::$fnName($user->name,$request->project,$request->path);
            $stream = ShellOutput::writeToFile($cmd,$user->name);
            if($stream === 0){
                $this->applicationRepository->applicationSetDeployed($request->project);
                return (new ApiResponse())->success([
                    'project'=>$request->project,
                ]);
            }
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());

        }
        }
    }

    private function getValueTryBlock(string $project,string $command, string $frontName){ //front name is name of form input
        try{
            $user=auth()->user();
            $cmd = ShellCmdBuilder::$command($user->name,$project);
            $values = shell_exec($cmd);
            return (new ApiResponse())->success([
                $frontName=>$values,
                'project'=>$project,
            ]);
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());

        }
    }
    private function dbTryCatchBlock (string $project,string $command,?string $password=null,?string $customQuery =null){
        $cmdNameArr = [
            'dbAndUserCreate'=>'databbase and user created',
            'dbAndPrivilegeCreate'=> 'databbase created',
            'dbCustomQuery'=>'database command executed',
        ];
        $databaseName = str_replace("-","_",$project);
        $dbUserExists = $this->userRepository->userHasRepositoryUser();
        if($command === 'dbCreate' && $dbUserExists){
            dd(1,$dbUserExists);
            $command = 'dbAndPrivilegeCreate';
        }else if($command === 'dbCreate'){
            dd(2,$dbUserExists);
            $command = 'dbAndUserCreate';
        }

        try {
            $user=auth()->user();
            $cmd = ShellCmdBuilder::$command($user->name,$databaseName,$password,$customQuery);
            $stream = ShellOutput::writeToFile($cmd,$user->name);

           if($stream === 0){ 
                    if($command === 'dbAndUserCreate'){
                        $this->userRepository->updateRepositoryUser();//TEST IN RASPBERY IF DB SHELL COMMANDS WORK
                    }
                    if($command === 'dbAndUserCreate' || $command === 'dbAndPrivilegeCreate') {
                        $this->applicationRepository->applicationAddDatabase($project);
                    }
                return (new ApiResponse())->success([
                    'status'=>$cmdNameArr[$command],
                    'project'=>$project,
                ]);
                // return redirect()->route('showShell',['project'=>$project])->with('status',$cmdNameArr[$command]);
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());

            // return redirect()->route('showShell',['project'=>$project])->with('danger','something went wrong '.$exception->getMessage());
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
            'configCashe'=>'Configuration cached successfully!'
        ];

        try {
            $user=auth()->user();
            $cmd = ShellCmdBuilder::$command($user->name,$project,$dynamicCmdValAfterCdRoute);
            
            $stream = ShellOutput::writeToFile($cmd,$user->name);  
           if($stream === 0){
            return (new ApiResponse())->success([
                'status'=>$cmdNameArr[$command],
                'project'=>$project,
                'comand'=>$cmd
            ]);
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
  }
}