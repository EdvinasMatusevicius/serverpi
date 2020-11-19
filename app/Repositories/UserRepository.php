<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Facades\UserFacade;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function userHasRepositoryUser(){
        $user = Auth::user();
        return $user->has_db_user;
    }
    public function updateRepositoryUser(){
        $user = Auth::user();

        /**
         * @var Authenticatable $user 
         */
        $user->update(['has_db_user'=>1]);
    }
    public function saveUserLogoPath(?string $path = null){
        $user = Auth::user();
        /**
         * @var Authenticatable $user 
         */
        $user->update(['logo'=>$path]);
    }
    public function deleteUser($request){
        $user = User::find(Auth::user()->id);
        UserFacade::logout($request);
        $user->delete();
    }
    
}