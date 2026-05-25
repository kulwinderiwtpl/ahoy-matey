<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReactionController;
use App\Http\Controllers\PostReplyController;
use App\Http\Controllers\SpacesController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/inviteUser/{user}', [InviteController::class, 'showRegistrationForm'])->name('inviteUser')->middleware('signed');

Route::middleware('auth')->group(function () {

    Route::get('/explore', [HomeController::class, 'explore'])->name('explore');
    Route::get('/', [HomeController::class, 'index'])->name('home');

    /* search */
    Route::get('/search/{search}', [HomeController::class, 'search'])->name('search');

    Route::prefix('user')->group(function () {

        /*
        |---------------
        | Profile Routes
        |---------------
        */

        Route::get('/setting', [UserController::class, 'setting'])->name('setting');
        Route::post('/setting', [UserController::class, 'updateSetting'])->name('update-setting');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/change-cover', [UserController::class, 'changeCover'])->name('change-cover');

        /* notfication Route */

        Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
        Route::post('/notifications', [UserController::class, 'readNotifications'])->name('read-notifications');

        /* invites routes */
        Route::get('/invites', [InviteController::class, 'index'])->name('invites');
        Route::post('/invites', [InviteController::class, 'sendInvitesEmail'])->name('send-invites');
    });

    /* member route */
    Route::get('member/{id}', [UserController::class, 'member'])->name('member');

    /*
    |---------------
    | Spaces Routes
    |---------------
    */

    Route::get('/spaces/{id}', [SpacesController::class, 'showSpaces'])->name('show-spaces');
    Route::post('/spaces/{id}', [SpacesController::class, 'changeCover'])->name('spaces-cover');
    Route::delete('/spaces/{id}', [SpacesController::class, 'leaveSpace'])->name('leave-space');
    Route::post('/spaces', [SpacesController::class, 'store'])->name('add-space');

    /* post routes */
    Route::resources(['posts' => PostController::class,]);

    /* reply  routes */
    Route::resource('postreplies', PostReplyController::class)->only('store', 'update', 'destroy');

    /* reaction routes */
    Route::post('/reaction', [PostReactionController::class, 'index'])->name('post-react');
});



Route::namespace("App\Http\Controllers\Admin")->prefix('admin')->group(function () {
Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('admin.dashboard');
    Route::get('/profile', 'HomeController@adminprofile')->name('admin.profile');
    Route::post('/profile', 'HomeController@adminStore')->name('admin.profile');
    Route::post('/password', 'HomeController@adminPassword')->name('admin.password');
    Route::get('/user', 'UserController@index')->name('admin.user');
    Route::get('/edit', 'UserController@edit')->name('admin.edit');
    Route::post('/delete-user', 'HomeController@deleteUser')->name('admin.delete-user');
    Route::post('/user/delete', 'UserController@delete')->name('admin.delete');
    Route::post('/user/update', 'UserController@update')->name('admin.update');

    /*
	===== Admin Post Routes
	*/

    Route::get('/user/view-post/{id}', 'UserController@viewPosts')->name('admin.view-post');
    Route::post('/user/save-post', 'UserController@storePost')->name('admin.save-post');
    Route::post('/user/update-post', 'UserController@updatePost')->name('admin.update-post');
    Route::post('/user/delete-post', 'UserController@destroy')->name('admin.delete-post');
	Route::post('/user/leave-space', 'UserController@leaveSpace')->name('admin.leave-space');
    Route::get('/user/edit-post', 'UserController@editPost')->name('admin.edit-post');
    Route::match(['get', 'post', 'put'], 'user/update-post', 'UserController@updatePost')->name('admin.edit-update');

    /*
	===== Admin Spaces Routes
	*/

    Route::post('/user/space-post', 'SpacesController@storeSpaces')->name('admin.space-post');
    Route::get('/user/space-show', 'SpacesController@showSpaces')->name('admin.space-show');

    /*
	===== Ends
	*/

    /*
	===== Spaces Routes
	*/
    Route::post('/delete-spaces', 'UserController@destroySpaces')->name('admin.delete-spaces');

    /*
	===== Ends
	*/

    Route::post('/user/change-cover', 'UserController@changeCover')->name('admin.change-cover');
});

    Route::namespace('Auth')->group(function () {
        Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
        Route::post('/login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');
		Route::get('logout', 'LoginController@logout')->name('admin.logout');
    });
});

Route::get('test', fn () => view('emails.invitation'))->name('test');
