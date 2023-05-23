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


Route::prefix('/Firebase/')->group(function(){
    Route::post('/send-otp', [FirebaseController::class, 'sendOTP']);
    Route::post('/verify-otp', [FirebaseController::class, 'verifyOTP']);
    Route::get('/construct', [FirebaseController::class, '__construct']);

});

Route::prefix('/Users/')->group(function(){
    Route::apiResource('User' , App\Http\Controllers\api\UserController::class) ;
    Route::get('construct' , [\App\Http\Controllers\api\UserController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\UserController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/WalkThrows/')->group(function(){
    Route::apiResource('WalkThrow' , App\Http\Controllers\api\Walk_thorwController::class) ;
    Route::get('construct' , [\App\Http\Controllers\api\Walk_thorwController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\Walk_thorwController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Attachments/')->group(function(){
    Route::apiResource('Attachment' , App\Http\Controllers\api\AttachmentController::class) ;
    Route::get('construct' , [\App\Http\Controllers\api\AttachmentController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\AttachmentController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Comments/')->group(function(){
    Route::apiResource('Comment' , App\Http\Controllers\api\CommentController::class) ;
    Route::get('construct' , [\App\Http\Controllers\api\CommentController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\CommentController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Projects/')->group(function(){
    Route::apiResource('Project' , App\Http\Controllers\api\ProjectController::class) ;
    Route::put('projects/{id}' , [\App\Http\Controllers\api\ProjectController::class , 'update'] )->name('update');
    Route::get('construct' , [\App\Http\Controllers\api\ProjectController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\ProjectController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Tasks/')->group(function(){
    Route::apiResource('Task' , App\Http\Controllers\api\TaskController::class);
    Route::get('construct' , [\App\Http\Controllers\api\TaskController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\TaskController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/FeaturedTasks/')->group(function(){
    Route::apiResource('FeaturedTask' , App\Http\Controllers\api\Featured_TaskController::class) ;
    Route::get('construct' , [\App\Http\Controllers\api\Featured_TaskController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\Featured_TaskController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Notifications/')->group(function(){
    Route::apiResource('Notification' , App\Http\Controllers\api\NotificationController::class);
    Route::get('construct' , [\App\Http\Controllers\api\NotificationController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\NotificationController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Promotions/')->group(function(){
    Route::apiResource('Promotion' , App\Http\Controllers\api\PromotionController::class) ;
    Route::get('construct' , [\App\Http\Controllers\api\PromotionController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\PromotionController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Contacts/')->group(function(){
    Route::apiResource('Contact' , App\Http\Controllers\api\ContactController::class);
    Route::get('construct' , [\App\Http\Controllers\api\ContactController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\ContactController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Projects/Users/')->group(function(){
    Route::post('attach', [\App\Http\Controllers\Api\ProjectUserController::class, 'attachUser']);
    Route::post('detach', [\App\Http\Controllers\Api\ProjectUserController::class, 'detachUser']);
    Route::put('state', [\App\Http\Controllers\Api\ProjectUserController::class, 'updateUserState']);
    Route::get('construct' , [\App\Http\Controllers\api\ProjectUserController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\ProjectUserController::class , 'verifyToken'] )->name('verifyToken');
});


Route::prefix('/Tasks/Users/')->group(function(){
    Route::post('attach', [\App\Http\Controllers\Api\TaskUserController::class, 'attachUser']);
    Route::post('detach', [\App\Http\Controllers\Api\TaskUserController::class, 'detachUser']);
    Route::put('state', [\App\Http\Controllers\Api\TaskUserController::class, 'updateUserState']);
    Route::get('construct' , [\App\Http\Controllers\api\TaskUserController::class , '__construct'] )->name('construct');
    Route::post('verifyToken' , [\App\Http\Controllers\api\TaskUserController::class , 'verifyToken'] )->name('verifyToken');
});
