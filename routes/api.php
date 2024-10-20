<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HallSeatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeatClassController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAccessMiddleware;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', function () {
    $name = \request('name');
    $mail = \request('mail');
    $age = \request('age');
    $language = \request('language');
    return
        response([
            'data'=>[
                \request()->all()
            ]
        ],200);
});

    Route::middleware('auth:sanctum')->group(function (){


        Route::prefix('/artist')->group(function (){

            Route::post('/',[ArtistController::class,'store']);
            Route::patch('/{artist}',[ArtistController::class,'update']);
            Route::delete('/{artist}',[ArtistController::class,'destroy']);

        });

        Route::prefix('/concerts')->group(function (){
            Route::post('/store',[ConcertController::class,'store']);
            Route::patch('/{concert}',[ConcertController::class,'update']);
            Route::delete('/{concert}',[ConcertController::class,'destroy']);
        });

        Route::prefix('/roles')->group(function (){

            Route::get('/',[RoleController::class,'index'])
                ->middleware(CheckAccessMiddleware::class.':read-roles');

            Route::get('/{role}',[RoleController::class,'show'])
                ->middleware(CheckAccessMiddleware::class.':read-roles');

            Route::post('/store',[RoleController::class,'store'])
                ->middleware(CheckAccessMiddleware::class.':create-roles');

            Route::patch('/{role}',[RoleController::class,'update'])
                ->middleware(CheckAccessMiddleware::class.':update-roles');

            Route::delete('/{role}',[RoleController::class,'destroy'])
                ->middleware(CheckAccessMiddleware::class.':delete-roles');

        });

        Route::prefix('/seat-classes')->group(function (){
            Route::get('/',[SeatClassController::class,'index']);
        });

        Route::prefix('/categories')->group(function (){

            Route::post('/store',[CategoryController::class,'store'])
                ->middleware(CheckAccessMiddleware::class.':create_categories');

            Route::patch('/{category}',[CategoryController::class,'update']);
            Route::delete('/{category}',[CategoryController::class,'destroy']);

        });

        Route::prefix('/halls')->group(function (){
            Route::get('/',[HallController::class,'index']);
            Route::post('/store',[HallController::class,'store']);
            Route::get('/{hall}',[HallController::class,'show']);
            Route::patch('/{hall}',[HallController::class,'update']);
            Route::delete('{hall}',[HallController::class,'destroy']);

        });
        Route::get('/halls/{hall}/seats',[HallSeatController::class,'index']);
        Route::post('/halls/{hall}/seats/store',[HallSeatController::class,'store']);
        Route::patch('/halls/{hall}/seats',[HallSeatController::class,'update']);
        Route::delete('halls/{hall}/seats/{seat_class}',[HallSeatController::class,'destroy']);

        Route::prefix('/users')->group(function (){

            Route::get('/',[UserController::class,'index']);
            Route::get('/{user}',[UserController::class,'show']);
        });


        Route::delete('/logout',[LoginController::class,'destroy']);

    });

    Route::prefix('artist')->group(function (){

        Route::get('/',[ArtistController::class,'index']);
        Route::get('/{artist}',[ArtistController::class,'show']);
    });

    Route::prefix('/concerts')->group(function (){
        Route::get('/',[ConcertController::class,'index']);
        Route::get('/{concert}',[ConcertController::class,'show']);
    });

    Route::prefix('category')->group(function (){

        Route::get('/',[CategoryController::class,'index']);
        Route::get('/{category}',[CategoryController::class,'show']);
    });


    Route::post('/register',[RegisterController::class,'store']);
    Route::post('/login',[LoginController::class,'store']);

