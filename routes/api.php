<?php

use Illuminate\Http\Request;

Route::get('clear-db', 'LayoutController@clearDatabase');
Route::get('layouts', 'LayoutController@getLayouts');
Route::post('layouts', 'LayoutController@saveLayout');
