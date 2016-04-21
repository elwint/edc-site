<?php

/* // User login/register
Route::post('/user/login', 'UserController@loginUser');
Route::get('/user/logout', 'UserController@logoutUser');
Route::get('/user/register', 'UserController@registerUser');
Route::post('/user/register', 'UserController@registerUser');

// User profile
Route::get('/user', 'UserController@showUser');
Route::post('/user', 'UserController@editUser');
Route::get('/user/:id', 'UserController@showProfile');
Route::post('/usercomment', 'UserController@addUserComment');
Route::post('/usercomment/:id', 'UserController@addProfileComment');

// Reviews
Route::get('/review/:id', 'ReviewController@show');
Route::get('/reviews', 'ReviewController@all');

// Products
Route::get('/product/:id', 'ProductController@show');
Route::get('/products', 'ProductController@all');
Route::get('/products/new', 'ProductController@create');
Route::post('/insertProduct', 'ProductController@insert'); */

Route::get('/', 'Home@show');
Route::get('/:page', 'NormalPage@show');