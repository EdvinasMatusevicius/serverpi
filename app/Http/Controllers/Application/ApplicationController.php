<?php

namespace App\Http\Controllers\Application;

use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationStoreRequest;
use App\Repositories\ApplicationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    private $applicationRepository;
    public function __construct(ApplicationRepository $applicationRepository)
    {
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
       $cmd = ShellCmdBuilder::gitClone($user->name,$data['applicationName'],$data['giturl']);
       $stream = ShellOutput::writeToFile($cmd);

        if($stream ===0){
            $output = $this->applicationRepository->saveWithRelation($request->only('applicationName'));
            return redirect()->route('panel',['project'=>$request->applicationName])->with('status','Your project cloned successfully '.$output);
       }
       return back()->with('danger','Error accured')->withInput();

    } catch (Exception $exception) {
       return back()->with('danger','something went wrong '.$exception->getMessage())->withInput();

    }
    }
}
