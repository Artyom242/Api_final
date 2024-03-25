<?php

use App\Http\Controllers\Api\File\FileController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Middleware\Api\AuthMiddleware;
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

Route::post('authorization', [UserController::class, 'authorization']);
Route::post('registration', [UserController::class, 'registration']);

Route::middleware([AuthMiddleware::class])->group(function (){
    Route::get('logout', [UserController::class, 'logout']);
    //files
    Route::post('files', [FileController::class, 'uploadFile']);
    Route::get('files/disk', [FileController::class, 'getAllFiles']);
    Route::get('files/shared', [FileController::class, 'getUserFiles']);
    Route::patch('files/{file:file_id}', [FileController::class, 'editFile'])->can('edit', 'file');
    Route::get('files/{file:file_id}', [FileController::class, 'downloadFile'])->can('download', 'file');
    Route::delete('files/{file:file_id}', [FileController::class, 'deleteFile'])->can('delete', 'file');
    Route::post('files/{file:file_id}/accesses', [FileController::class, 'addAccessFile'])->can('add', 'file');
    Route::delete('files/{file:file_id}/accesses', [FileController::class, 'blockAccessFile'])->can('block', 'file');
});
