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
    return view('layouts.applogin');
});

Auth::routes();

Route::get('/home', 'StudentController@index')->name('home');


Route::resource('student', 'StudentController');

Route::post('student/update', 'StudentController@update')->name('student.update');

Route::get('student/destroy/{id}', 'StudentController@destroy');
