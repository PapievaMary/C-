<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Comment
Route::resource('comment',CommentController::class);
Route::controller(CommentController::Class)->group(function(){
    Route::post('comment', 'store');
    Route::get('comment', 'index')->name('comment.index');
    Route::get('comment/{comment}/accept', 'accept');
    Route::get('comment/{comment}/reject', 'reject');
});


//Article
Route::resource('article', ArticleController::class)->middleware('auth:sanctum');

//Auth
Route::get('signin', [AuthController::class, 'signin']);
Route::post('registr', [AuthController::class, 'registr']);
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('signup', [AuthController::class, 'signup']);
Route::get('logout', [AuthController::class, 'logout']);

//MainController
Route::get('/', [MainController::class, 'index']);
Route::get('/full-img/{img}', [MainController::class, 'show']);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
