<?php

namespace App\Http\Controllers\Application\Panel;

use App\Facades\ShellCmdBuilder;
use App\Facades\ShellOutput;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShellController extends Controller
{
 
    public function showShell(): View
    {
        $projektas = 'serverpi';
        return view('panel.shellCommandsSection',[
            'project'=> $projektas

        ]);
    }

    public function gitPull(Request $request){
        try {
            $user=auth()->user();
            // $cmd = ShellCmdBuilder::gitPull($user->name,$request->project);
            $cmd = ShellCmdBuilder::gitPull($request->project,'');//for now pulls serverpi whitch is not in user folder\
            $stream = ShellOutput::writeToFile($cmd);
           if($stream === 0){
            return redirect()->route('showShell',['project'=>$request->project])->with('status','git command finished ');
        }
        throw new Exception('error accured');
        } catch (Exception $exception) {
            return redirect()->route('showShell',['project'=>$request->project])->with('danger','something went wrong '.$exception->getMessage());
        }
    }

  
}