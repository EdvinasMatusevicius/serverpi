<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function index(): View
    {
        $projektas = 'serverpi';                     /////<--dinamiskai gauti produkto name
        return view('panel.controlPanel',[
            'project'=> $projektas
        ]);
    }

}
