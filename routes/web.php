<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Ruta POST para el formulario de registro
Route::post('/register', [PruebaController::class, 'register'])->name('register');

// Ruta POST para el formulario de login
Route::post('/login', [PruebaController::class, 'login'])->name('login');

// Ruta para mostrar el perfil del usuario
Route::get('/profile', [PruebaController::class, 'showProfile'])->name('profile');

// Ruta para manejar la carga del CV
Route::post('/upload-cv', [PruebaController::class, 'uploadCV'])->name('uploadCV');
