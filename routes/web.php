<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;

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
    return view('login');
});

Route::post('/login', [AuthController::class, "login"]);
Route::get('/logout', [AuthController::class, "logout"])->middleware("auth");

Route::get("/blogs/create", [BlogController::class, "create"])->name("blog.create")->middleware("auth");
Route::get("/blogs/list", [BlogController::class, "list"])->name("blog.list")->middleware("auth");
Route::post("/blogs/store", [BlogController::class, "store"])->middleware("auth");
Route::post("/blogs/delete", [BlogController::class, "delete"])->middleware("auth");
Route::post("/blogs/update", [BlogController::class, "update"])->middleware("auth");
Route::get("/blogs/fetch/{page}", [BlogController::class, "fetch"])->middleware("auth");
Route::get("/blogs/edit/{id}", [BlogController::class, "edit"])->middleware("auth");
