<?php

namespace App\Http\Controllers\Application;

use App\Facades\ShellCmdBuilder;
use App\Http\Controllers\Controller;
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

    public function create(Request $request)
    {
        try {
       $user=auth()->user();
       $cmd = ShellCmdBuilder::gitClone($user->name,$request->applicationName,$request->giturl);
    //    $output =shell_exec($cmd);
       //SAVE TO DATABASE
       $output = $this->applicationRepository->saveWithRelation($request->only('applicationName'));
       
        return redirect()->route('panel',['project'=>$request->applicationName])->with('status','Your project cloned successfully '.$output);
    } catch (Exception $exception) {
        return redirect()->route('newApplication')->with('danger','something went wrong '.$exception->getMessage());
    }
    }
}
