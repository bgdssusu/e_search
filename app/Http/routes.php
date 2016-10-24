<?php
Route::get('/destroy',['uses' => 'NavigationController@destroyDB']);

Route::get('/create',['as'=>'create', 'uses' => 'NavigationController@checkDB']);
Route::get('/create/doit',['as'=>'doit', 'uses' => 'NavigationController@createDB']);

Route::get('/search/{prop1?}/{prop2?}/{prop3?}/{prop4?}',['as'=>'search', 'uses' => 'NavigationController@navigate']);
Route::post('search/{prop1?}/{prop2?}/{prop3?}/{prop4?}', 'NavigationController@navigatePost');