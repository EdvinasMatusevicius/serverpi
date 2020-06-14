<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Admin;
use App\AdminApplication;
use App\Application;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class ApplicationRepository
{
    public function saveWithRelation(array $data):void
    {   
        $user = auth()->user();
        
        if($user instanceof User){
            $user->applications()->create($data);
        }
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
    public function userApplicationsList():array
    {
        $user = Auth::user();
        $userApplications = $user->applications;
        $filteredApplications = $userApplications->map(function($app){
            return $app->only(['applicationName','slug']);
        });
        return $filteredApplications->toArray();

    }
    public function applicationBelongsToUser($project){
        $user = Auth::user();
        $userApp = false;
        if($user instanceof User){
            $userApp = Application::query()->where('user_id','=',$user->id)
            ->where('slug','=',$project)->exists();
        }else if($user instanceof Admin){
            $userApp = AdminApplication::query()->where('admin_id','=',$user->id)
            ->where('slug','=',$project)->exists();
        }
        return $userApp;

        //PABAIGTI VALIDACIJA AR USERIS GALI LEISTI COMANDAS ANT KONKRETAUS PROJEKTO
    }
}