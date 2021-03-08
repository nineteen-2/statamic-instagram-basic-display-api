<?php

Route::prefix('nineteen-ig/')->name('nineteen-ig.')->group(function () {
    Route::get('/', 'InstagramLoginController@index')->name('index');
    Route::get('/logout', 'InstagramLoginController@logout')->name('logout');
    Route::get('/auth', 'InstagramLoginController@callback')->name('callback');
});
