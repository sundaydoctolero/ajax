<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::resource('posts','PostsController');
Route::post('posts/changeStatus', array('as' => 'changeStatus', 'uses' => 'PostsController@changeStatus'));


Route::resource('links','LinkController');

Route::get('ajax-image-upload', 'ImageController@index');
Route::post('ajax-image-upload', 'ImageController@ajaxImage');
Route::delete('ajax-remove-image/{filename}', 'ImageController@deleteImage');