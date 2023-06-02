<?php

use App\Http\Controllers\FirebaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('/firebase/')->group(function(){
    Route::post('/send-otp', [FirebaseController::class, 'sendOTP']);
    Route::post('/verify-otp', [FirebaseController::class, 'verifyOTP']);
});

Route::prefix('users')->as('users.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\UserController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\UserController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\UserController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\UserController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\UserController@destroy')->name('destroy');
});





Route::prefix('walkthroughts')->as('walkthroughes.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\Walk_thorwController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\Walk_thorwController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\Walk_thorwController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\Walk_thorwController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\Walk_thorwController@destroy')->name('destroy');
});



Route::prefix('attachments')->as('attachments.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\AttachmentController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\AttachmentController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\AttachmentController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\AttachmentController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\AttachmentController@destroy')->name('destroy');
});


Route::prefix('comments')->as('comments.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\CommentController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\CommentController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\CommentController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\CommentController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\CommentController@destroy')->name('destroy');
});

Route::prefix('projects')->as('projects.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\ProjectController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\ProjectController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\ProjectController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\ProjectController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\ProjectController@destroy')->name('destroy');
});

Route::prefix('tasks')->as('tasks.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\TaskController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\TaskController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\TaskController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\TaskController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\TaskController@destroy')->name('destroy');
});

Route::prefix('featured-tasks')->as('featured-tasks.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\Featured_TaskController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\Featured_TaskController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\Featured_TaskController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\Featured_TaskController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\Featured_TaskController@destroy')->name('destroy');
});

Route::prefix('notifications')->as('notifications.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\NotificationController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\NotificationController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\NotificationController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\NotificationController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\NotificationController@destroy')->name('destroy');
});

Route::prefix('promotions')->as('promotions.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\PromotionController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\PromotionController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\PromotionController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\PromotionController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\PromotionController@destroy')->name('destroy');
});

Route::prefix('contacts')->as('contacts.')->group(function () {
    Route::get('/', 'App\Http\Controllers\api\ContactController@index')->name('index');
    Route::post('/', 'App\Http\Controllers\api\ContactController@store')->name('store');
    Route::get('/{id}', 'App\Http\Controllers\api\ContactController@show')->name('show');
    Route::put('/{id}', 'App\Http\Controllers\api\ContactController@update')->name('update');
    Route::delete('/{id}', 'App\Http\Controllers\api\ContactController@destroy')->name('destroy');
});
Route::prefix('projects/users')->as('projects-users.')->group(function(){
    Route::post('attach', [\App\Http\Controllers\Api\ProjectUserController::class, 'attachUser']);
    Route::post('detach', [\App\Http\Controllers\Api\ProjectUserController::class, 'detachUser']);
});


Route::prefix('tasks/users')->as('tasks-users.')->group(function(){
    Route::post('attach', [\App\Http\Controllers\Api\TaskUserController::class, 'attachUser']);
    Route::post('detach', [\App\Http\Controllers\Api\TaskUserController::class, 'detachUser']);
});

//Route::prefix('users')->as('users.')->group(function(){
//    Route::apiResource('/', 'App\Http\Controllers\api\UserController')->parameters([
//        'users' => 'id'
//    ]);
//});

//Route::prefix('users')->as('users.')->group(function(){
//    // Define the index route without an ID parameter
//    Route::get('/', 'App\Http\Controllers\api\UserController@index')->name('index');
//
//    // Define the store route without an ID parameter
//    Route::post('/', 'App\Http\Controllers\api\UserController@store')->name('store');
//
//    // Define the resource route with an ID parameter for the remaining functions
//    Route::apiResource('/{id}', 'App\Http\Controllers\api\UserController')->except(['index', 'store']);
//
//});

//Route::prefix('users')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\UserController::class) ;
//});
//Route::prefix('walkthroughts')->as('walkthroughes.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\Walk_thorwController::class) ;
//});
//Route::prefix('attachments')->as('attachments.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\AttachmentController::class) ;
//});


//Route::prefix('comments')->as('comments.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\CommentController::class) ;
//});
//
//
//Route::prefix('projects')->as('projects.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\ProjectController::class) ;
//});
//
//
//Route::prefix('tasks')->as('tasks.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\TaskController::class);
//});
//
//
//Route::prefix('featured-tasks')->as('featured-tasks.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\Featured_TaskController::class) ;
//});
//
//
//Route::prefix('notifications')->as('notifications.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\NotificationController::class);
//});
//
//
//Route::prefix('promotions')->as('promotions.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\PromotionController::class) ;
//});
//
//
//Route::prefix('contacts')->as('contacts.')->group(function(){
//    Route::apiResource('/' , App\Http\Controllers\api\ContactController::class);
//});



