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
        $routeToSh = '/var/www/sh/'.$user->name;

        // $routeToSh = 'cd /var/www/sh/dude';
        $output = file_get_contents($routeToSh.'/shell.txt');
        $err = file_get_contents($routeToSh.'/shellerrors.txt');
        // $output = shell_exec($routeToSh." && cat shell.txt 2>&1");
        // $err = shell_exec($routeToSh." && cat shellerrors.txt 2>&1");

        return (new ApiResponse())->success([
            'output'=>$output,
            'errors'=>$err
        ]);
    }
}
