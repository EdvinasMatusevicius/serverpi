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
    public function saveWithRelation(array $data):void
    {   
        $user = auth()->user();
        
        if($user instanceof User){
            $application=$user->applications()->create($data);
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

}