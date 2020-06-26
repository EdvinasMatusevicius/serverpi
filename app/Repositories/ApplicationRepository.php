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
    public function saveWithRelation(array $data)
    {   
        $user = auth()->user();
        
        return $user->applications()->create($data);
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
    public function applicationBelongsToUser($application){
        $user = Auth::user();
        $userApp = false;
        if($user instanceof User){
            $userApp = Application::query()->where('user_id','=',$user->id)
            ->where('slug','=',$application)->exists();
        }else if($user instanceof Admin){
            $userApp = AdminApplication::query()->where('admin_id','=',$user->id)
            ->where('slug','=',$application)->exists();
        }
        return $userApp;
    }
    public function applicationSlugExists($slug){
        $userApp = Application::where('slug','=',$slug)->exists();
        $adminsApp = AdminApplication::where('slug','=',$slug)->exists();
        if($userApp || $adminsApp){
            return true;
        }else{
            return false;
        }
    }
    public function applicationHasDatabase($slug){
        $applicationClass = $this->applicationClass();
        $app = $applicationClass::where('slug','=',$slug)->value('database');
        if($app){
            return $app;
        }else{
            return false;
        }
    }
    public function applicationAddDatabase($slug){
        $applicationClass = $this->applicationClass();
        $dbName = str_replace("-","_",$slug);
        $applicationClass::where('slug','=',$slug)->update(['database'=>$dbName]);;
    }
    private function applicationClass($user = NULL){
        $user = $user ?? auth()->user();
        if($user instanceof User){
            return new Application;
        }else if($user instanceof Admin){
            return new AdminApplication;
        }
    }
}