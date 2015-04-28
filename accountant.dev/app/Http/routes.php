<?php

Route::get('/', 'PagesController@getUserKveds');
Route::controllers([
	'auth' => 'Auth\AuthController',
        'pages' => 'PagesController',
        'kved' => 'KvedController',
        
]); 

Route::get('/sync/count', 'SyncController@getCount');
Route::post('/sync/data', 'SyncController@postData');
Route::post('/sync/confirm', 'SyncController@postConfirm');
