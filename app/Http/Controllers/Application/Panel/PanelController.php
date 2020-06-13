<?php

namespace App\Http\Controllers\Application\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function index(Request $request): View
    {                     /////<--dinamiskai gauti produkto name
        return view('panel.controlPanel',[
            'project'=> $request->project
        ]);
    }

}
