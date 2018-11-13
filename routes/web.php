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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('list','ListController@index');
Route::post('delete','ListController@delete');
Route::post('book_search','ListController@book');
Route::post('create','ListController@create');
Route::get('search','ListController@search');
Route::get('users','ListController@users');
Route::get('users_list/{id}','ListController@users_list');

Auth::routes();

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
