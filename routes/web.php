<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts/login');
});


Route::view('Allposts', 'Allposts');
Route::view('Addpost', 'Addpost');
