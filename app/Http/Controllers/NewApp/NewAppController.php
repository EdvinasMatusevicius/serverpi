<?php

namespace App\Http\Controllers\NewApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewAppController extends Controller
{
    public function index(): View
    {
        return view('newApp.formNewApp');
    }

    public function create(Request $request) //return type
    {
        dd($request->toArray());
    }
}
