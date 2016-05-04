<?php

Route::not_found('NormalPage', 'showNotFound');
Route::forbidden('NormalPage', 'showForbidden');

Route::post('/register', 'User@register');
Route::post('/login', 'User@login');
Route::get('/logout', 'User@logout');

Route::get('/', 'Home@show');
Route::get('/cups', 'Cups@show');
Route::get('/p/:linktitle', 'Post@show');
Route::get('/results/:linktitle', 'Result@show');

Route::get('/api/setserver/:bool', 'Api@setServer');

Route::get('/:page', 'NormalPage@show');