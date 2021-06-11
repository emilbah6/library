<?php

use App\Http\Controllers\BookController;
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

/** These endpoints are used for DB actions */
Route::prefix('v1')->group(function () {
    Route::get('/books', [BookController::class, "index"])->name("get.books");
    Route::get('/books/{id}', [BookController::class, "viewSpecificBook"])->name("view.specific.book");
    Route::post('/books', [BookController::class, "createBook"])->name("add.book");
    Route::patch('/books/{id}', [BookController::class, "editBook"])->name('edit.book');
    Route::delete('/books/{id}', [BookController::class, "deleteBook"])->name("delete.book");
});

/** This endpoint is used for searching books via the API */
Route::get('/external-books', [BookController::class, "externalBookSearch"])->name("external.book.search");
