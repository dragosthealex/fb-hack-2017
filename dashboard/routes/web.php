<?php

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
Auth::routes();
Route::get('/login', function() {
    return redirect()->to('/oauth/facebook');
})->name('login');
Route::get('/register', function() {
    return redirect()->to('/oauth/facebook');
})->name('register');
Route::get('/logout', function() {
    Auth::logout();
    return redirect()->to('/');
})->name('logout');


Route::get('/oauth/{provider}', 'Auth\OAuthController@redirectToProvider');
Route::get('/oauth/callback/{provider}', 'Auth\OAuthController@handleProviderCallback');
Route::get('/oauth/success/{provider}', 'Auth\OAuthController@success');


// App routes
Route::get('/', function () {
    if(Auth::check()) {
        return redirect()->to('/home');
    }
    return view('welcome');
});
Route::get('/home', 'HomeController@index');
Route::get('/videos/{id}', 'VideoController@show');

// Hooks
Route::get('/hooks/live-video/test', 'HooksController@test_live_video');