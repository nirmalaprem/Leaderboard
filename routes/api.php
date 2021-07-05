<?php
use App\Http\Controllers\ApiauthController;
use App\Http\Controllers\LuserController;
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

//Route::resource('users', LuserController::class);
// Public routes
Route::post('/register', [ApiauthController::class, 'register']);
Route::get('/users', [LuserController::class, 'index']);
Route::get('/users/{id}', [LuserController::class, 'show']);
Route::post('/login', [ApiauthController::class, 'login']);

// pretected routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/users', [LuserController::class, 'store']);
    Route::put('/users/{id}', [LuserController::class, 'update']);
    Route::delete('/users/{id}', [LuserController::class, 'destroy']);
    Route::post('/logout', [ApiauthController::class, 'logout']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


