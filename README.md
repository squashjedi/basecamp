# Basecamp

## About Basecamp

Basecamp provides a great starting place for your Laravel project with all of the following:-
* It has a fluent interface to OAuth authentication with Facebook using the Laravel Socialite package.
* Built in emails to authenticate users, password resets, resend validation code and a welcome email.
* An area for logged in users to update their settings
* A webmaster's admin panel to crud users using an API.

## Install

via Composer

``` bash
composer require squashjedi/basecamp
```

### Configuration

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
php artisan vendor:publish --tag=basecamp-views
php artisan vendor:publish --tag=basecamp-components
php artisan vendor:publish --tag=basecamp-sass
php artisan migrate
php artisan db:seed --class="Squashjedi\\Basecamp\\DatabaseSeeder"
npm install
npm install font-awesome
```

Add these Vue components in `resources/assets/js/app.js`:
``` bash
Vue.component('app-pagination',
	require('./components/vendor/squashjedi/basecamp/Pagination.vue'));
Vue.component('app-admin-users',
	require('./components/vendor/squashjedi/basecamp/admin/Users.vue'));
Vue.component('app-modal-delete',
	require('./components/vendor/squashjedi/basecamp/ModalDelete.vue'));
Vue.component('app-modal-create',
	require('./components/vendor/squashjedi/basecamp/ModalCreate.vue'));
Vue.component('app-modal-update',
	require('./components/vendor/squashjedi/basecamp/ModalUpdate.vue'));
Vue.component('app-alert',
	require('./components/vendor/squashjedi/basecamp/Alert.vue'));
Vue.component('app-user-settings-account',
	require('./components/vendor/squashjedi/basecamp/user/settings/Account.vue'));
Vue.component('app-user-settings-password',
	require('./components/vendor/squashjedi/basecamp/user/settings/Password.vue'));
Vue.component('app-user-settings-password-modal',
	require('./components/vendor/squashjedi/basecamp/user/settings/PasswordModal.vue'));
```

Add these at the bottom of `resources/assets/sass/app.scss`:
``` bash
// Font Awesome
@import "node_modules/font-awesome/scss/font-awesome.scss";

// Basecamp variables
@import "vendor/squashjedi/basecamp/variables";

// Basecamp extras
@import "vendor/squashjedi/basecamp/extras";
```

Run the following commands:
``` bash
npm run dev
```

Delete `resources/views/welcome.blade.php`

Delete: all routes in `routes/web.php`

Replace `app/User.php` with:
``` bash
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Squashjedi\Basecamp\App\Notifications\ResetPasswordNotification;
use Squashjedi\Basecamp\App\Social;
use Squashjedi\Basecamp\App\Role;
use Squashjedi\Basecamp\App\PasswordCode;

class User extends Authenticatable
{
    use Notifiable;

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
        'verified', 'name', 'email', 'password', 'verify_token'
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
    public function passwordCode()
    {
        return $this->hasOne(PasswordCode::class, 'email', 'email');
    }
}
```

Create a new file `app/Http/Middleware/AuthWebmaster.php` with the following content:
``` bash
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthWebmaster
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::Check()) {
            $isWebmaster = false;
        } elseif (!Auth::user()->roles()->first()) {
            $isWebmaster = false;
        } elseif (Auth::user()->roles()->first()->role != 'webmaster') {
            $isWebmaster = false;
        } else {
            $isWebmaster = true;
        }
        if (!$isWebmaster) {
            return redirect(route('settings'));
        }

        return $next($request);
    }
}
```

Add this towards the bottom of `app/Http/Kernel.php`:
``` bash
protected $routeMiddleware = [
    // Other middlewares...

    'auth.webmaster' => \App\Http\Middleware\AuthWebmaster::class,
];
```

Then finally add the following to `resources/lang/en/validation.php`:
``` bash
return [
    // Other error messages...

    'password_match'        => 'This doesn\'t match your current password',
];
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.