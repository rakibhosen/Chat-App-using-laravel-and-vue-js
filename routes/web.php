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
Route::get('/userlist', 'MessageController@userList');
Route::get('/usermessage/{id}', 'MessageController@userMessage');
Route::post('/sendmessage', 'MessageController@sendMessage');
Route::get('/deletesinglemsg/{id}', 'MessageController@DeleteSingleMessage');
Route::get('/deletemultiplemsg/{id}', 'MessageController@DeleteMultipleMessage');

// Broadcast::channel('chat.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
