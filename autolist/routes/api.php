<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AutolistController;
use App\Http\Controllers\Api\V1\FileController;

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

Route::group(['prefix' => '/v1'], function () {
    Route::resource('autolist', AutolistController::class, ['except' => ['create', 'edit']]);
    Route::get('photo/{id}/{field}/{fileId}', [FileController::class, 'show']);
});

// Route::get(config('bitrix24.REDIRECT_URI'), function (Request $request, Bitrix24 $bitrix) {
//     $code = $request->input('code');
//     $result = $bitrix->getFirstAccessToken($code);
//     // Bitrix::saveSettings($result); // TODO:
//     return redirect()->route('/');
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
