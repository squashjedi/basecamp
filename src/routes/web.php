<?php

Route::namespace('Squashjedi\Basecamp\App\Http\Controllers')->group(function() {

	Route::group(['middleware' => ['web']], function () {

		Route::get('/', 
			function () {
			    return view('squashjedi/basecamp::index');
			});

		// Socialite
		Route::get('/auth/{provider}',
			'Auth\OAuthController@redirectToProvider');

		Route::get('/auth/{provider}/callback',
			'Auth\OAuthController@handleProviderCallback');

		// Auth\RegisterController
		Route::get('/register',
			'Auth\RegisterController@showRegistrationForm')
				->name('register');

		Route::get('/register/signup',
			'Auth\RegisterController@showRegistrationForm')
				->name('register');

		Route::post('/register/signup',
			'Auth\RegisterController@register');

		Route::get('/verify/{email}/{verifyToken}',
			'Auth\RegisterController@verifyAccount')
				->name('verifyAccount');

		Route::get('/verify',
			'Auth\RegisterController@resendVerification')
				->name('resendVerification');

		Route::post('/verify',
			'Auth\RegisterController@resendVerificationEmail')
				->name('resendVerificationEmail');

		// Auth\LoginController
		Route::get('/login',
			'Auth\LoginController@showLoginForm')
				->name('login');

		Route::post('/login',
			'Auth\LoginController@login');

		Route::post('/logout',
			'Auth\LoginController@logout')
				->name('logout');

		// Auth\ForgotPasswordController
		Route::get('/password/reset',
			'Auth\ForgotPasswordController@showLinkRequestForm')
				->name('password.request');

		Route::post('/password/email',
			'Auth\ForgotPasswordController@sendResetLinkEmail')
				->name('password.email');

		// Auth\ResetPasswordController
		Route::get('/password/reset/{token}',
			'Auth\ResetPasswordController@showResetForm')
				->name('password.reset');	

		Route::post('/password/reset',
			'Auth\ResetPasswordController@reset');

		Route::group(['middleware' => ['auth']], function () {	

			// User\Settings\AccountController
			Route::get('/user',
				'User\Settings\AccountController@edit')
					->name('user');

			// User\Settings\AccountController
			Route::get('/user/settings',
				'User\Settings\AccountController@edit')
					->name('settings');

			Route::get('/user/settings/account',
				'User\Settings\AccountController@edit')
					->name('settings.account');

			// User\Settings\Account\DeactivateController
			Route::get('/user/settings/account/deactivate',
				'User\Settings\Account\DeactivateController@edit')
					->name('settings.account.deactivate');

			// User\Settings\PasswordController
			Route::get('/user/settings/password',
				'User\Settings\PasswordController@edit')
					->name('settings.password');

			// User\Settings\Password\ForgotController
			Route::get('/user/settings/password/forgot',
				'User\Settings\Password\ForgotController@edit')
					->name('settings.password.forgot');

			// User\Settings\Password\Forgot\ResetController
			Route::get('/user/settings/password/forgot/{email}/{code}',
				'User\Settings\Password\Forgot\ResetController@edit')
					->name('settings.password.reset');

			Route::group(['middleware' => ['webmaster']], function () {

				// Admin\UsersController
				Route::resource('/admin/users', 
					'Admin\UsersController', [
						'names' => [ 'index' => 'admin.users' ]
					]);

			});

		});



	});

	Route::group(['middleware' => ['auth:api']], function () {

		Route::prefix('api')->group(function () {

			// Api\Admin\UsersController
			Route::resource('/v1/admin/users',
				'Api\Admin\UsersController');

			// Api\User\Settings\AccountController
			Route::resource('/v1/user/settings/account',
				'Api\User\Settings\AccountController');

			// Api\User\Settings\Account\DeactivateController
			Route::post('/v1/user/settings/account/deactivate',
				'Api\User\Settings\Account\DeactivateController@update');

			// Api\User\Settings\PasswordController
			Route::post('/v1/user/settings/password/send',
				'Api\User\Settings\PasswordController@send');

			Route::post('/v1/user/settings/password/update',
				'Api\User\Settings\PasswordController@update');

			Route::post('/v1/user/settings/password/update-forgot',
				'Api\User\Settings\PasswordController@updateForgot');

		});

	});

});
