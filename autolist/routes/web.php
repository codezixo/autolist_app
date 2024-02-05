<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/autolist/ping', function() { return 'Pong!'; });
Route::get('/autolist', function () {
    return view('welcome', ['authData' => []]);
});

Route::post('/autolist/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::post('/autolist/', function () {
    // $authData = [
    //     'AUTH_ID' => $this->request->input('AUTH_ID'),
    //     'REFRESH_ID' => $this->request->input('REFRESH_ID'),
    //     'AUTH_EXPIRES' => $this->request->input('AUTH_EXPIRES'),
    // ];
    // return view('welcome', ['authData' => json_encode($authData)]);
// });

// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
