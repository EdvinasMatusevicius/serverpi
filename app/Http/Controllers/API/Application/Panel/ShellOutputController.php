<?php

namespace App\Http\Controllers\API\Application\Panel;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;

class ShellOutputController extends Controller
{
    
    public function getShellFileVals(Request $request){
        $user=auth()->user();
        // $routeToSh = 'cd /var/www/sh/'.$user->name;
        // $output = shell_exec($routeToSh." && cat shell.txt 2>&1");
        // $err = shell_exec($routeToSh." && cat shellerrors.txt 2>&1");
        $routeToSh = 'cd /var/www/sh/dude';
        $output = shell_exec($routeToSh." && cat shell.txt 2>&1");
        $err = shell_exec($routeToSh." && cat shellerrors.txt 2>&1");

        return (new ApiResponse())->success([
            'output'=>$output,
            'errors'=>$err
        ]);
    }
}
