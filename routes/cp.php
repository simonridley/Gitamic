<?php

use Illuminate\Support\Facades\Route;

Route::get('gitamic/status', function () {
    return view('gitamic::status', ['wrapper_class' => 'max-w-full']);
})->name('gitamic.status');

Route::group(['prefix' => 'gitamic/api'], function () {
    Route::get('status', 'GitamicApiController@status');
    Route::get('actions/{type}', 'GitamicApiController@actions');
});
