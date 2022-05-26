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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*Route::get('/students', function () {
    return view('students.index');
})->name('students.index')->middleware('auth');*/

Route::prefix('students')->group(function(){
	Route::get('', 'App\Http\Controllers\StudentController@index')->name('student.index')->middleware('auth');
	Route::get('create', 'App\Http\Controllers\StudentController@create')->name('student.create')->middleware('auth');
	Route::post('store', 'App\Http\Controllers\StudentController@store')->name('student.store')->middleware('auth');
	Route::get('edit/{id}', 'App\Http\Controllers\StudentController@edit')->name('student.edit')->middleware('auth');
	Route::post('update/{id}', 'App\Http\Controllers\StudentController@update')->name('student.update')->middleware('auth');
	Route::get('show/{id}', 'App\Http\Controllers\StudentController@show')->name('student.show')->middleware('auth');
	Route::post('delete/{id}', 'App\Http\Controllers\StudentController@destroy')->name('student.delete')->middleware('auth');
	Route::post('change', 'App\Http\Controllers\StudentController@change')->name('student.change')->middleware('auth');
	Route::post('refresh', 'App\Http\Controllers\StudentController@refresh')->name('student.refresh')->middleware('auth');
});

Route::prefix('projects')->group(function(){
	//Index
	Route::get('', 'App\Http\Controllers\ProjectController@index')->name('project.index')->middleware('auth');
	//create
	Route::get('create', 'App\Http\Controllers\ProjectController@create')->name('project.create')->middleware('auth');
	Route::post('store', 'App\Http\Controllers\ProjectController@store')->name('project.store')->middleware('auth');
	//edit
	Route::get('edit/{project}', 'App\Http\Controllers\ProjectController@edit')->name('project.edit')->middleware('auth');
	Route::post('update/{project}', 'App\Http\Controllers\ProjectController@update')->name('project.update')->middleware('auth');
	//delete
	Route::post('destroy/{project}', 'App\Http\Controllers\ProjectController@destroy')->name('project.destroy')->middleware('auth');
	//show
	Route::get('show/{project}', 'App\Http\Controllers\ProjectController@show')->name('project.show')->middleware('auth');	
	Route::get('status/{project}', 'App\Http\Controllers\ProjectController@status')->name('project.status')->middleware('auth');
	Route::post('addgroup', 'App\Http\Controllers\ProjectController@addgroup')->name('project.addgroup')->middleware('auth');
});

Route::prefix('groups')->group(function(){
	//Index
	Route::get('', 'App\Http\Controllers\GroupController@index')->name('group.index')->middleware('auth');
	//create
	Route::get('create', 'App\Http\Controllers\GroupController@create')->name('group.create')->middleware('auth');
	Route::post('store/{group}', 'App\Http\Controllers\GroupController@store')->name('group.store')->middleware('auth');
	Route::post('store', 'App\Http\Controllers\GroupController@store')->name('group.store')->middleware('auth');
	//edit
	Route::get('edit/{group}', 'App\Http\Controllers\GroupController@edit')->name('group.edit')->middleware('auth');
	Route::post('update/{group}', 'App\Http\Controllers\GroupController@update')->name('group.update')->middleware('auth');
	//delete
	Route::post('destroy/{group}/{page?}', 'App\Http\Controllers\GroupController@destroy')->name('group.destroy')->middleware('auth');
	//show
	Route::get('show/{group}', 'App\Http\Controllers\GroupController@show')->name('group.show')->middleware('auth');
});