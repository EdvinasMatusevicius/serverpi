<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return view('welcome');
});

Auth::routes(); //add except for at the moment unecesery routes   delete/update etc


Route::middleware('auth:web')->group(function(){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::namespace('Application')->group(function(){
        Route::get('/new-application', 'ApplicationController@index')->name('newApplication');
        Route::post('/new-application', 'ApplicationController@create')->name('newApplicationCreate');

        Route::namespace('Panel')->group(function(){
            Route::middleware('checkOwner')->get('/{project}/panel', 'PanelController@index')->name('panel');
            Route::middleware('checkOwner')->get('/{project}/shell', 'ShellController@showShell')->name('showShell');

            //----------------------------------------------------------------------------------------------------- SHELL
            $shellRoutes = ['git_pull','composer_install','app_key_generate','app_storage_link','db_migrate','dump_autoload','db_seed',
        'custom_artisan','get_env_values','copy_env_example','create_env_file','write_to_env_file','npm_install','app_key_generate'
        ,'nginx_config','db_create','db_custom_query',]; 
            // -----------------------------------------------------------------------------------------------------
            
                foreach ($shellRoutes as $route) {
                    Route::middleware('checkOwner')->match(['get','post'],'/{project}/shell/'.$route,'ShellController@'.$route)->name($route);
                }
        });
    });

});