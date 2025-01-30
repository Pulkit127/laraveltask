<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function() {
    Route::get('/','index')->name('index');
    Route::get('create','create')->name('create');
    Route::post('store','store')->name('store');
    Route::get('edit/{id}','edit')->name('edit');
    Route::get('delete/{id}','delete')->name('delete');
    Route::post('update','update')->name('update');
    Route::get('final-submit','finalSubmit')->name('finalSubmit');
    Route::get('export','export')->name('export');
    Route::post('import','import')->name('import');
});
