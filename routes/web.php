<?php

use Illuminate\Support\Facades\Route;

/*
 * social
 */
Route::get('/login/{social}','Auth\LoginController@redirectToProvider')->where('social','facebook|google');
Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')->where('social','facebook|google');


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



Auth::routes(['verify' => true]);
Route::get('/', 'HomeController@index')->name('home');

Route::post('upload', 'UploadController@store');
Route::delete('upload', 'UploadController@destroy');

Route::get('share-with-me', 'NoteController@shareWithMe')->name('notes.share-with-me');
Route::post('notes/{note}/share', 'NoteController@share')->name('notes.share');
Route::delete('notes/{note}/remove-access', 'NoteController@removeAccess')->name('notes.remove-access');

Route::resource('files', 'FileController');
Route::resource('notes', 'NoteController');



