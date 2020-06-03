<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Admin;
use App\Application;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class ApplicationRepository
{
    public function saveWithRelation(array $data):Application
    {   
        $user = Auth::user();
        $userDB = User::find($user->id);
        $application=$userDB->applications()->create($data);

dd($application);


        
        if($user instanceof User){
            $application->user()->sync($user->id);
        }
        return $application;
    }
    public function saveWithRelationAdmin(array $data):Application
    {
        $user = Auth::user();
        $application = Application::query()->create($data);

        if($user instanceof Admin){
            $application->admin()->sync($user);
        }
        return $application;
    }

}