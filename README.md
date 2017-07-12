<h1 align="center">Basecamp</h1>

<p align="center">
<a href="https://packagist.org/packages/squashjedi/basecamp"><img src="https://poser.pugx.org/squashjedi/basecamp/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/squashjedi/basecamp"><img src="https://poser.pugx.org/squashjedi/basecamp/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/squashjedi/basecamp"><img src="https://poser.pugx.org/squashjedi/basecamp/license.svg" alt="License"></a>
</p>

## About Basecamp

Basecamp provides a great starting place for your Laravel project with all of the following:-
* It has a fluent interface to OAuth authentication with Facebook using the [Laravel Socialite](https://github.com/laravel/socialite) package.
* Built in emails to authenticate users, password resets, resend validation code and a welcome email.
* An area for logged in users to update their settings.
* A webmaster's admin panel to CRUD Users.
* A Users API.

## Install

via Composer

``` bash
composer require squashjedi/basecamp
```

## Configuration

After installing the Basecamp library, register `Squashjedi\Basecamp\BasecampServiceProvider::class` in your `config/app.php` configuration file:

``` bash
'providers' => [
    // Other service providers...

    Squashjedi\Basecamp\BasecampServiceProvider::class,
],
```

You will also need to add credentials for the OAuth services your application utilises. These credentials should be placed in your `config/services.php` configuration file and should use the key `facebook`. For example:

``` bash
'facebook' => [
    'client_id' => 'your-facebook-app-id',
    'client_secret' => 'your-facebook-app-secret',
    'redirect' => 'http://your-callback-url',
],
```

Run the following commands:
``` bash
php artisan vendor:publish --tag=basecamp
php artisan migrate
php artisan db:seed --class="Squashjedi\\Basecamp\\DatabaseSeeder"
npm install
npm install font-awesome
```

Add these Vue components in `resources/assets/js/app.js`:
``` bash
Vue.component('basecamp-pagination',
    require('./components/basecamp/Pagination.vue'));

Vue.component('basecamp-notify',
    require('./components/basecamp/Notify.vue'));

Vue.component('basecamp-modal-delete',
    require('./components/basecamp/ModalDelete.vue'));

Vue.component('basecamp-admin-users',
    require('./components/basecamp/admin/users/Index.vue'));

Vue.component('basecamp-admin-users-fields',
    require('./components/basecamp/admin/users/Fields.vue'));

Vue.component('basecamp-admin-users-create',
    require('./components/basecamp/admin/users/Create.vue'));

Vue.component('basecamp-admin-users-edit',
    require('./components/basecamp/admin/users/Edit.vue'));

Vue.component('basecamp-user-settings-account',
    require('./components/basecamp/user/settings/account/Account.vue'));

Vue.component('basecamp-user-settings-account-deactivate',
    require('./components/basecamp/user/settings/account/Deactivate.vue'));

Vue.component('basecamp-user-settings-account-deactivate-modal',
    require('./components/basecamp/user/settings/account/DeactivateModal.vue'));

Vue.component('basecamp-user-settings-password',
    require('./components/basecamp/user/settings/password/Password.vue'));

Vue.component('basecamp-settings-password-forgot',
    require('./components/basecamp/user/settings/password/Forgot.vue'));

Vue.component('basecamp-user-settings-password-reset',
    require('./components/basecamp/user/settings/password/Reset.vue'));
```

Add these at the bottom of `resources/assets/sass/app.scss`:
``` bash
// Font Awesome
@import "node_modules/font-awesome/scss/font-awesome.scss";

// Basecamp variables
@import "basecamp/variables";

// Basecamp extras
@import "basecamp/extras";
```

Run the following command:
``` bash
npm run dev
```

Delete `resources/views/welcome.blade.php`.

Delete all routes in `routes/web.php`.

Replace `app/User.php` with:
``` bash
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Squashjedi\Basecamp\App\Notifications\ResetPasswordNotification;
use Squashjedi\Basecamp\App\Social;
use Squashjedi\Basecamp\App\Role;
use Squashjedi\Basecamp\App\PasswordReset;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'verified', 'name', 'email', 'password', 'verify_token', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the social networks for the user.
     */
    public function social()
    {
        return $this->hasMany(Social::class);
    }

    /**
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get the password code for the user.
     */
    public function passwordReset()
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }
}
```

Then finally add the following to `resources/lang/en/validation.php`:
``` bash
return [
    // Other error messages...

    'password_match'        => 'This doesn\'t match your current password',
];
```

Install `Laravel Passport` https://packagist.org/packages/laravel/passport.

Add this middleware to `app/Http/Kernel`:
``` bash
protected $middlewareGroups = [
    'web' => [
        // Other middleware classes...

        \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
    ],
];
```

## Usage

The database seed creates 1000 users.

The webmaster's login details are:
``` bash
Email: me@example.com
Password: 123456
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.