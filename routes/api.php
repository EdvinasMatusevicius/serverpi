<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = $request->user();
    return [
        'name'=> $user->name,
        'email'=>$user->email,
        'has_db_user'=>$user->has_db_user,
    ];
});
Route::namespace('API\Auth')->prefix('auth')->group(function (){
    
    Route::middleware('guest:api')->group(function (){
        Route::post('register', 'AuthenticationController@register');
        Route::post('login', 'AuthenticationController@login');
    });


    Route::middleware('auth:api')->group(function (){
        Route::post('logout', 'AuthenticationController@logout');

    });
});
Route::namespace('API')->group(function(){
    Route::namespace('Application')->group(function(){
        Route::get('/shared-apps','ApplicationController@getSharedAppsWithUsername');
    });
});
Route::namespace('API')->middleware('auth:api')->group(function (){
    
    Route::post('user/logo','AccountController@saveIcon');
    Route::delete('user/delete','AccountController@delete');

    Route::namespace('Application')->group(function(){
        Route::post('/new-application', 'ApplicationController@create')->name('newApplicationCreate');
        Route::get('/app-list', 'ApplicationController@getAppList');
        Route::middleware('checkOwner')->group(function(){
            Route::post('/{project}/share', 'ApplicationController@setShareStatus');
            Route::post('/{project}/image', 'ApplicationController@saveAppImage');
            Route::post('/{project}/description', 'ApplicationController@saveAppDescription');
            Route::get('/{project}/description', 'ApplicationController@getAppDescription');
            Route::get('/{project}/share-status', 'ApplicationController@getShareStatus');
            Route::get('/{project}/database', 'ApplicationController@getAppDatabase');
            Route::delete('/{project}/delete_app', 'ApplicationController@deleteApp');
        });

        Route::namespace('Panel')->group(function(){
            Route::get('/shell-values','ShellOutputController@getShellFileVals');

            //----------------------------------------------------------------------------------------------------- SHELL
            $shellRoutes = ['git_pull','composer_install','app_key_generate','app_storage_link','db_migrate','dump_autoload','db_seed',
            'custom_artisan','get_env_values','copy_env_example','create_env_file','write_to_env_file','npm_install','app_key_generate'
            ,'nginx_config','db_create','db_and_user_create','db_custom_query','config_cache'
    ]; 
            // -----------------------------------------------------------------------------------------------------
            
                foreach ($shellRoutes as $route) {
                    Route::middleware('checkOwner')->match(['get','post'],'/{project}/shell/'.$route,'ShellController@'.$route)->name($route);
                }
        });
    });

});

