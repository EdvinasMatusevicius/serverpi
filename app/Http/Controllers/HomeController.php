<?php

namespace App\Http\Controllers;

use App\Repositories\ApplicationRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $applicationRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository=$applicationRepository;
    }

    
    public function index()
    {
        $userApplications = $this->applicationRepository->userApplicationsList();
        return view('home',['list'=>$userApplications]);
    }
}
