<?php

namespace App\Http\Controllers\API\Application;

use App\Facades\NginxConfigBuilder;
use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ApplicationStoreRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\ApplicationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    private $applicationRepository;
    public function __construct(ApplicationRepository $applicationRepository)
    {

        $this->applicationRepository=$applicationRepository;
    }


    public function create(ApplicationStoreRequest $request)
    {
        try {
       $user=auth()->user();
       $data = $request->getData();
       $cmd = ShellCmdBuilder::gitClone($user->name,$data['slug'],$data['giturl']);
       $stream = ShellOutput::writeToFile($cmd,$user->name);

        if($stream ===0){
            $fieldsArr =['applicationName','slug','language','giturl'];
            // if(isset($data['database'])){
            //     array_push($fieldsArr,'database'); // would break app if named db before creating it(where db name used as bool to check if db created)
            // };
            $this->applicationRepository->saveWithRelation(Arr::only($data,$fieldsArr));
            return (new ApiResponse())->success([
                'project'=>$data['slug'],
            ]);
       }
       return (new ApiResponse())->exception('Error occurred while saving application');

        } catch (Exception $exception) {
           return (new ApiResponse())->exception($exception->getMessage());

        }
    }
    public function getAppList(Request $request)
    {
        try {
            $applicationsList = $this->applicationRepository->userApplicationsList();
            return (new ApiResponse())->success([
               'appList'=> $applicationsList
            ]);

        } catch (Exception $exception) {
           return (new ApiResponse())->exception($exception->getMessage());
        }
    }
    public function getAppDatabase(Request $request){
        try {
            $appDatabase = $this->applicationRepository->applicationHasDatabase($request->project);
            return (new ApiResponse())->success([
                'database'=> $appDatabase
             ]);
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
         }
    }
    public function deleteApp(Request $request){
        try{
            $user=auth()->user();
            shell_exec(ShellCmdBuilder::deleteApplication($user->name,$request->project));
            $database = $this->applicationRepository->applicationHasDatabase($request->project);
            $appDeployed = $this->applicationRepository->applicationIsDeployed($request->project);
            if($database){
                shell_exec(ShellCmdBuilder::deleteProjectDb($database));
            }
            if($appDeployed){
                shell_exec(NginxConfigBuilder::deleteNginxConfig($request->project));
                shell_exec(NginxConfigBuilder::restartNginx());
            }
            $this->applicationRepository->deleteApplication($request->project);
            return (new ApiResponse())->success('App deleted');
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
         }
    }
}
