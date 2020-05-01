<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['auth', 'role:admin']], function () use ($router) {
  //ADMIN
  Route::get('user-admin', 'UserController@getAdmins');
  Route::post('admin/register', 'AuthController@registerAdmin');
  Route::delete('users/{id}', 'UserController@destroy');
  Route::put('users/other/{id}', 'UserController@updateOther');

  Route::resource('majors', 'MajorController');
  Route::resource('faculties', 'FacultyController');
  Route::resource('news', 'NewsController');
  Route::resource('sliders', 'SliderController');
});

Route::group(['middleware' => 'auth'], function () use ($router) {
  //PROFILE
  Route::get('profile', 'UserController@profile');
  Route::put('profile/edit', 'UserController@update');
  Route::put('profile/photo', 'UserController@editPhoto');
  Route::put('profile/password', 'UserController@editPassword');
  //USER
  Route::resource('users', 'UserController', ['except' => ['getAdmins', 'destroy', 'updateOther']]);
});

Route::post('register', 'AuthController@register'); // register mahasiswa
Route::post('login', 'AuthController@login'); // login all
