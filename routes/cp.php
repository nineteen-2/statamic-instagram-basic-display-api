<?php

use NineteenSquared\Instagram\Http\Controllers\InstagramLoginController;
use NineteenSquared\Instagram\Http\Controllers\InstagramLogoutController;

Route::prefix('nineteen-ig/')->name('nineteen-ig.')->group(function () {
    Route::get('/', [InstagramLoginController::class, 'index'])->name('index');
    Route::get('/logout', InstagramLogoutController::class)->name('logout');
    Route::get('/auth', [InstagramLoginController::class, 'callback'])->name('callback');
});