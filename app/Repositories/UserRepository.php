<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Admin;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function userHasRepositoryUser(){
        $user = Auth::user();
        return $user->has_db_user;
    }
    
}