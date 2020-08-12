<?php

namespace App\Http\Controllers\API;

use App\Facades\ShellCmdBuilder;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Repositories\ApplicationRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    private $userRepository;
    private $applicationRepository;

    public function __construct(UserRepository $userRepository, ApplicationRepository $applicationRepository)
    {
        $this->userRepository = $userRepository;
        $this->applicationRepository = $applicationRepository;
    }

    public function delete(Request $request){
        try {
            $user=Auth::user();
            ShellCmdBuilder::userFolder($user->name,true);
            $userApps = $this->applicationRepository->userApplicationsList();
            foreach ($userApps as $app){
                if($app['database']){
                    ShellCmdBuilder::deleteProjectDb($app['database']);
                }
            }
            ShellCmdBuilder::deleteDbUser($user->name);
            $this->userRepository->deleteUser($request);
            return (new ApiResponse())->unauthorized('Account deleted'); 
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }
}
