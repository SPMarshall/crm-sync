<?php


Route::get('/', 'KvedController@index');
Route::get('/synchronize/', 'SyncController@synchronize');