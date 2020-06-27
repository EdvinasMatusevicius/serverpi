<?php
declare(strict_types=1);
namespace App\Repositories;

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
    
}