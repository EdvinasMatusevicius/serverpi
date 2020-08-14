<?php

namespace App\Http\Controllers\API;

use App\Facades\NginxConfigBuilder;
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
    //ISTRINTI NGINX CONF 
    public function delete(Request $request){
        try {
            $user=Auth::user();
            $cmdUser = ShellCmdBuilder::userFolder($user->name,true);
            $response = shell_exec($cmdUser);
            $userApps = $this->applicationRepository->userApplicationsList();
            foreach ($userApps as $app){
                if($app['database']){
                    shell_exec(NginxConfigBuilder::deleteNginxConfig($app['slug']));
                    shell_exec(ShellCmdBuilder::deleteProjectDb($app['database']));
                }
            }
            $cmdDbUser = ShellCmdBuilder::deleteDbUser($user->name);
            shell_exec($cmdDbUser);
            $this->userRepository->deleteUser($request);
            return (new ApiResponse())->unauthorized('Account deleted'); 
            return (new ApiResponse())->unauthorized($response); 
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }
}
