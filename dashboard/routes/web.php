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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function() {
    return redirect()->to('/oauth/facebook');
});
Route::get('/register', function() {
    return redirect()->to('/oauth/facebook');
});

Route::get('/home', 'HomeController@index');

Route::get('/oauth/{provider}', 'Auth\OAuthController@redirectToProvider');
Route::get('/oauth/callback/{provider}', 'Auth\OAuthController@handleProviderCallback');
Route::get('/oauth/success/{provider}', 'Auth\OAuthController@success');