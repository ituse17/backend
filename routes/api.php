<?php

use Illuminate\Http\Request;

Route::get('layouts', 'LayoutController@getLayouts');
Route::post('layouts', 'LayoutController@saveLayout');
