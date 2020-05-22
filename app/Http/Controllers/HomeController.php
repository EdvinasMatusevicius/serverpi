<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $output1; 
        $output2; 
        $output3; 
    //  shell_exec('cd /mnt/c/xampp/htdocs && sudo mkdir steineristestfolder');
    $output = shell_exec('cd / && ls');
    dd($output);
    //  echo $output1;
    //  system('sudo mkdir steineristestfolder',$output2);
    //  echo $output2;
    // system('cd /mnt/c/xampp/htdocs && ls',$output3);
    // echo $output3;
    // $code = system('ls',$output4);
    // dd($output4);
    // dd($output4);
    //  system('cd /mnt/c/xampp/htdocs/steineristestfolder');
     
    //  system('sudo git clone https://github.com/EdvinasMatusevicius/Basic-chat.git',$output3);
    //  echo $output3;
     return view('home');
    }
}
