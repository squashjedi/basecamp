<?php

Route::namespace('Squashjedi\Basecamp\App\Http\Controllers')->group(function() {

	Route::group(['middleware' => ['web']], function () {

		Route::get('/', function () {
			    return view('squashjedi/basecamp::index');
			});

		// Socialite
		Route::get('/auth/{provider}', 'Auth\OAuthController@redirectToProvider');
		Route::get('/auth/{provider}/callback', 'Auth\OAuthController@handleProviderCallback');

		Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
		Route::post('/login', 'Auth\LoginController@login');
		Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
		Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
		Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
		Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
		Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
		Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');

		// Override Registration Routes...
		Route::get('/register/signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
		Route::post('/register/signup', 'Auth\RegisterController@register');

		Route::get('/verify/{email}/{verifyToken}', 'Auth\RegisterController@verifyAccount')->name('verifyAccount');
		Route::get('/verify', 'Auth\RegisterController@resendVerification')->name('resendVerification');
		Route::post('/verify', 'Auth\RegisterController@resendVerificationEmail')->name('resendVerificationEmail');

		Route::get('/user/settings', 'User\Settings\AccountController@edit')->name('settings');
		Route::get('/user/settings/account', 'User\Settings\AccountController@edit')->name('settings.account');
		Route::get('/user/settings/password', 'User\Settings\PasswordController@edit')->name('settings.password');

		Route::resource('/admin/users', 'Admin\UsersController', ['names' => [
			    'index' => 'admin.users'
			]]);

	});

	Route::resource('/api/admin/v1/users', 'Api\Admin\UsersController');
	Route::resource('/api/user/settings/v1/account', 'Api\User\Settings\AccountController');
	Route::post('/api/user/settings/v1/password/send-modal', 'Api\User\Settings\PasswordController@sendModal');
	Route::post('/api/user/settings/v1/password/code-modal', 'Api\User\Settings\PasswordController@codeModal');
	Route::post('/api/user/settings/v1/password/update-modal', 'Api\User\Settings\PasswordController@updateModal');
	Route::post('/api/user/settings/v1/password/update', 'Api\User\Settings\PasswordController@update');

});
