<?php

Route::get('/', 'Home@show');
Route::get('/cups', 'Cups@show');
Route::get('/:page', 'NormalPage@show');