<?php

Route::multilingual('/resources', function () {
    return view('resources.index');
})->name('resources.index');
