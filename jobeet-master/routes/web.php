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


Route::prefix('jobs')->middleware('auth')->group(function () {
    Route::get('index', 'JobsController@index')->name('jobs.index');
    Route::get('create', 'JobsController@create')->name('jobs.create');
    Route::post('store', 'JobsController@store')->name('jobs.store');
    Route::get('edit/{id}', 'JobsController@edit')->name('jobs.edit');
    Route::post('update/{id}', 'JobsController@update')->name('jobs.update');
    Route::get('delete/{id}', 'JobsController@delete')->name('jobs.delete');
});

Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('create', 'UsersController@create')->name('users.create');
    Route::post('store', 'UsersController@store')->name('users.store');
    Route::get('index', 'UsersController@index')->name('users.index');
    Route::get('edit/{id}', 'UsersController@edit')->name('users.edit');
    Route::post('update/{id}', 'UsersController@update')->name('users.update');
    Route::get('destroy/{id}', 'UsersController@destroy')->name('users.destroy');
    Route::get('apply/{id}', 'UsersController@apply')->name('users.apply');
    Route::post('storeApplication', 'UsersController@storeApplication')->name('users.storeApplication');
});

Route::get('top/index', 'TopController@index');


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/test', function () {
    return Auth::user()->id;
});
Route::get('test2', 'JobsController@store');
