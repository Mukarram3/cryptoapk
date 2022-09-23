<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\apiController;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {


    Route::post('signup',[AuthController::class,'signup']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('loginadmin',[AuthController::class,'loginadmin']);

    Route::post('logout',[AuthController::class,'logout']);
    Route::post('me',[AuthController::class,'me']);
    Route::post('refresh',[AuthController::class,'refresh']);


    Route::post('transfer',[apiController::class,'transfer']);
    Route::post('transferdetail',[apiController::class,'tranferdetails']);
    Route::post('findaccount',[apiController::class,'findaccount']);
    Route::post('profile',[apiController::class,'profile']);
    Route::post('checkbalance',[apiController::class,'checkbalance']);
    Route::post('userdetails',[apiController::class,'userdetails']);
    Route::post('addbalance',[apiController::class,'addbalance']);

    Route::post('sendcode',[apiController::class,'sebdverificationcode']);
    Route::post('delhistory',[apiController::class,'delhistory']);
    Route::get('test',function(){
        return 'dfdfd';
    });

});
