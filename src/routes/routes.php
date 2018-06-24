<?php

Route::namespace('MultiTenantLaravel\App\Http\Controllers')->middleware('web')->group(function() {

    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    // App Routes...
    Route::get('/', 'DashboardController@index')->name('dashboard')->middleware('multi-tenant');
    Route::post('select-tenant', 'DashboardController@selectTenant')->name('select-tenant')->middleware('multi-tenant');
    Route::post('change-tenant', 'DashboardController@changeTenant')->name('change-tenant')->middleware('multi-tenant');
});


