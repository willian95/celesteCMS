<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ImageController;

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
})->middleware("guest");

Route::post('/login', [AuthController::class, "login"]);
Route::get('/logout', [AuthController::class, "logout"])->middleware("auth");

Route::get("/home", function(){

    return view("dashboard");

})->name("home")->middleware("auth");

Route::get("/blogs/create", [BlogController::class, "create"])->name("blog.create")->middleware("auth");
Route::get("/blogs/list", [BlogController::class, "list"])->name("blog.list")->middleware("auth");
Route::post("/blogs/store", [BlogController::class, "store"])->middleware("auth");
Route::post("/blogs/delete", [BlogController::class, "delete"])->middleware("auth");
Route::post("/blogs/update", [BlogController::class, "update"])->middleware("auth");
Route::get("/blogs/fetch/{page}", [BlogController::class, "fetch"])->middleware("auth");
Route::get("/blogs/edit/{id}", [BlogController::class, "edit"])->middleware("auth");

Route::get("/products/create",  [ProductController::class, "create"])->name("product.create");
Route::view("/products/list", "products.list")->name("product.list")->middleware("auth");
Route::post("/products/store", [ProductController::class, "store"]);
Route::post("/products/delete", [ProductController::class, "delete"]);
Route::post("/products/update", [ProductController::class, "update"]);
Route::get("/products/fetch", [ProductController::class, "fetch"]);
Route::get("/products/edit/{id}", [ProductController::class, "edit"]);

Route::get("/staffs/create",  [StaffController::class, "create"])->name("staff.create");
Route::view("/staffs/list", "staffs.list")->name("staff.list")->middleware("auth");
Route::post("/staffs/store", [StaffController::class, "store"]);
Route::post("/staffs/delete", [StaffController::class, "delete"]);
Route::post("/staffs/update", [StaffController::class, "update"]);
Route::get("/staffs/fetch", [StaffController::class, "fetch"]);
Route::get("/staffs/edit/{id}", [StaffController::class, "edit"]);

Route::post("/upload/picture", [ImageController::class, "upload"]);
