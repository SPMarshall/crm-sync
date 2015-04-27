<?php

Route::get('/', 'PagesController@getUserKveds');
Route::controllers([
	'auth' => 'Auth\AuthController',
        'pages' => 'PagesController',
        'kved' => 'KvedController',
        
]); 

Route::get('/kved-facade/count/{operation}', 'KvedFacadeController@getCount');
Route::post('/kved-facade/kveds', 'KvedFacadeController@postKveds');
Route::post('/kved-facade/confirm-sync', 'KvedFacadeController@postConfirmSync');
