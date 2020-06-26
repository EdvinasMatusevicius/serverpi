<?php

namespace App\Http\Controllers\Application;

use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationStoreRequest;
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
        // $this->middleware('auth');

        $this->applicationRepository=$applicationRepository;
    }

    public function index(): View
    {
        return view('application.formApplication');
    }

    public function create(ApplicationStoreRequest $request)
    {
        try {
       $user=auth()->user();
       $data = $request->getData();
       //create unique file and give to write file
    //    $cmd = ShellCmdBuilder::gitClone($user->name,$data['slug'],$data['giturl']);
    //    $stream = ShellOutput::writeToFile($cmd,$user->name);

        // if($stream ===0){
            $fieldsArr =['applicationName','slug','language'];
            if(isset($data['database'])){
                array_push($fieldsArr,'database');
            };
            $application = $this->applicationRepository->saveWithRelation(Arr::only($data,$fieldsArr));
            return redirect()->route('panel',['project'=>$data['slug']])->with('status',$application->applicationName .' project cloned successfully ');
    //    }
       return back()->with('danger','Error accured')->withInput();

    } catch (Exception $exception) {
       return back()->with('danger','something went wrong '.$exception->getMessage())->withInput();

    }
    }
}
