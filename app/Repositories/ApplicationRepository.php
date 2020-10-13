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
    public function deleteApplication($application){
        $user = Auth::user();
        Application::query()->where('user_id','=',$user->id)
        ->where('slug','=',$application)->delete();
    }
    public function userApplicationsList():array
    {
        $user = Auth::user();
        $userApplications = $user->applications;
        $filteredApplications = $userApplications->map(function($app){
            return $app->only(['applicationName','slug','language','giturl','database','deployed']);
        });
        return $filteredApplications->toArray();

    }
    public function allSharedApplicationsAndUsersList(){
        $apps =Application::with('owner')
        ->where('share','=',true)
        ->where('deployed','=',true)->get();
        $appUserInfo = $apps->map(function($app){
            $appInfo = $app->only(['applicationName','slug','language']);
            $user = $app->only('owner')['owner']->only('name');
            return array_merge($appInfo,$user);
        });
        return $appUserInfo->toArray();
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
        if(!$this->applicationHasDatabase($slug))    
            $applicationClass = $this->applicationClass();
            $dbName = str_replace("-","_",$slug);
            return $applicationClass::where('slug','=',$slug)->update(['database'=>$dbName]);
    }
    public function applicationSetDeployed($slug){
        $applicationClass = $this->applicationClass();
        return $applicationClass::where('slug','=',$slug)->update(['deployed'=>1]);
    }
    public function applicationIsDeployed($slug){
        $applicationClass = $this->applicationClass();
        return $applicationClass::where('slug','=',$slug)->value('deployed');
    }
    public function applicationLanguage($slug){
        $applicationClass = $this->applicationClass();
        return $applicationClass::where('slug','=',$slug)->value('language');
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