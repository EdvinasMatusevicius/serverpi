<?php

namespace App\Http\Controllers\API\Application;

use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ApplicationStoreRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\ApplicationRepository;
use Exception;
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
       //create unique file and give to write file
       $cmd = ShellCmdBuilder::gitClone($user->name,$data['slug'],$data['giturl']);
       $stream = ShellOutput::runAndStreamCmd($cmd,$user->name);

        if($stream ===0){
            $fieldsArr =['applicationName','slug','language'];
            if(isset($data['database'])){
                array_push($fieldsArr,'database');
            };
            $this->applicationRepository->saveWithRelation(Arr::only($data,$fieldsArr));
            return (new ApiResponse())->success([
                'project'=>$data['slug'],
            ]);
            // return redirect()->route('panel',['project'=>$data['slug']])->with('status',$application->applicationName .' project cloned successfully ');
       }
       return (new ApiResponse())->exception('Error occurred while saving application');
    //    return back()->with('danger','Error accured')->withInput();

    } catch (Exception $exception) {
       return (new ApiResponse())->exception($exception->getMessage());

    //    return back()->with('danger','something went wrong '.$exception->getMessage())->withInput();

    }
    }
}
