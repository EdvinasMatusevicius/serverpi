<?php

namespace App\Http\Controllers\API;

use App\Facades\NginxConfigBuilder;
use App\Facades\ShellCmdBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserIconStoreRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\ApplicationRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    private $userRepository;
    private $applicationRepository;

    public function __construct(UserRepository $userRepository, ApplicationRepository $applicationRepository)
    {
        $this->userRepository = $userRepository;
        $this->applicationRepository = $applicationRepository;
    }
    public function saveIcon(UserIconStoreRequest $request){
        try {
            $logo = $request->file('logo');
            $path = $logo->store('userLogos');
            $this->deleteLogo();
            $this->userRepository->saveUserLogoPath($path);
            return (new ApiResponse())->success('Image saved');
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }

    }
    //ISTRINTI NGINX CONF 
    public function delete(Request $request){
        try {
            $user=Auth::user();
            $cmdUser = ShellCmdBuilder::userFolder($user->name,true);
            $response = shell_exec($cmdUser);
            $userApps = $this->applicationRepository->userApplicationsList();
            foreach ($userApps as $app){
                if($app['deployed']){
                    shell_exec(NginxConfigBuilder::deleteNginxConfig($app['slug']));
                }
                if($app['database']){
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
    private function deleteLogo(){
        $user=Auth::user();
        if(Storage::exists($user->logo)){
            Storage::delete($user->logo);
            $this->userRepository->saveUserLogoPath();
        }
    }
}
