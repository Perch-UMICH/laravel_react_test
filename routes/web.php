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

Route::resource('/', 'IndexController', ['only' => ['index']]);


Route::get('about', function () {
    return view('about');
});

Route::get('team', function () {
    return view('team');
});

Route::get('timeline', function () {
    return view('timeline');
});

Route::get('/dev', function () {
    return view('dev');
});

//Auth::routes();

// Google OAuth
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/home', 'HomeController@index')->name('home');

// Interested
Route::resource('interested', 'NewsletterController', ['only' => [
    'index', 'store'
]]);

//Route::get('users', 'UserController@index');
