<?php

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

Route::get('/gitsearch', 'GithubController@index');
Route::post('/githubUser', 'GithubController@searchByUsername');
Route::post('/githubUserAdd', 'GithubController@searchByUsernameAdd');
Route::post('/gituserAdd', 'GithubController@addGitUser');
Route::post('/gituserRemove', 'GithubController@removeGitUser');