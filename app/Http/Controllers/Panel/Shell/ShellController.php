<?php

namespace App\Http\Controllers\Panel\Shell;

use App\Helpers\ShellCmdBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShellController extends Controller
{
   private $shellCmdBuilder;

   public function __construct(ShellCmdBuilder $shellCmdBuilder)
   {
      $this->shellCmdBuilder = $shellCmdBuilder;
   }

   public function showShell(): View
   {
       $projektas = 'serverpi';
       return view('panel.shellCommandsSection',[
           'project'=> $projektas
           
       ]);
   }

 public function gitPull(){
    $cmd = $this->shellCmdBuilder->git('pull');
    $output = shell_exec($cmd);
    dd($output);
 }
}