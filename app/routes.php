<?php

Route::notFound('NormalPage', 'showNotFound');

Route::post('/register', 'User@register');
Route::post('/login', 'User@login');
Route::get('/logout', 'User@logout');

Route::get('/', 'Home@show');
Route::get('/cups', 'Cups@show');

Route::get('/api/setserver/:bool', 'Api@setServer');
Route::get('/:page', 'NormalPage@show');