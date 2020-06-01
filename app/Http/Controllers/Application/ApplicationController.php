<?php

namespace App\Http\Controllers\Application;

use App\Facades\ShellCmdBuilder;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        return view('application.formApplication');
    }

    public function create(Request $request)
    {
        try {
       $user=auth()->user();
       $cmd = ShellCmdBuilder::gitClone($user->name,$request->newApplicationName,$request->giturl);
       $output =shell_exec($cmd);
       //SAVE TO DATABASE
        return redirect()->route('panel',['project'=>$request->newApplicationName])->with('status','Your project cloned successfully '.$output);
    } catch (Exception $exception) {
        return redirect()->route('newApplication')->with('danger','something went wrong '.$exception->getMessage());
    }
    }
}
