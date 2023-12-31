<?php

use App\Http\Controllers\UserLocationController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::post('/components/import_excel', 'ComponentController@import_excel')->name('import');

Route::get('userlocation', [UserLocationController::class, 'index']);