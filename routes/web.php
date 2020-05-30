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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth:web')->group(function(){

    Route::namespace('Application')->group(function(){
        Route::get('/new-application', 'ApplicationController@index')->name('newApplication');
        Route::post('/new-application', 'ApplicationController@create')->name('newApplicationCreate');

        Route::namespace('Panel')->group(function(){
            Route::get('/{project}/panel', 'PanelController@index')->name('panel');
            
            $shellRoutes = ['gitpull','gitstatus']; 
            $projects = ['serverpi'];  //get all projects, add  permitions to user which he can access
            
            
            Route::get('/{project}/shell','ShellController@showShell')->name('showShell');
            foreach ($shellRoutes as $route) {
                Route::get('/{project}/shell/'.$route,'ShellController@'.$route)->name($route . $projects[0]);
            }
        });
    });

});