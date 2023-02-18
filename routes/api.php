<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRentController;
use Illuminate\Support\Facades\Log;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('user')->group(function(){

    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'authenticate']);

    Route::group(['middleware' => ['jwt.verify']], function() {

        // User routes
        Route::put('profile_edit/{id}', [UserController::class, 'profile_edit']);
        Route::delete('profile_delete/{id}', [UserController::class, 'delete']);
        
        //book routes

        Route::post('add_book', [BookController::class, 'add_book']);
        Route::post('edit_book', [BookController::class, 'edit_book']);
        Route::get('view_all_book', [BookController::class, 'view_all_book']);
        Route::delete('delete_book/{id}', [BookController::class, 'delete_book']);

        //Rent book routes
        
        Route::post('rent_book', [BookRentController::class, 'add_rent_book']);
        Route::put('return_rent_book/{b_id}', [BookRentController::class, 'return_rent_book']);
        Route::get('all_rented_detail', [BookRentController::class, 'all_rented_detail']);


    });

});