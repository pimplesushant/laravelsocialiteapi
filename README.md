# Laravel Socialite API
A powerful package designed to implement social signup and signin using access_token in Laravel. This package takes care of Facebook and Google+ social signup and signin as of now. We'll be adding more services soon.
## Installation
This package is availabe on composer. 
```composer require pimplesushant/laravelsocialiteapi```

Alternatively you can edit your ```composer.json``` and add 
"pimplesushant/laravelsocialiteapi": "^1.0"
and ```composer update```
## Usage
To use this package you'll need to follow required steps of [Laravel Passport](https://laravel.com/docs/passport). You can make following changes in files as follows :

**1. /.env**
```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_CLIENT_REDIRECT=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_CLIENT_REDIRECT=
GOOGLE_DEVELOPER_KEY=
```

Then, 
```
php artisan migrate
``` 
and 
```
php artisan passport:install
```

**2. /app/User.php**
```
use Laravel\Passport\HasApiTokens;
use HasApiTokens;
```

and 

```
public function social_accounts()
{
	return $this->hasMany(\Pimplesushant\Laravelsocialiteapi\SocialAccount::class)->with('social_accounts');
}
```

**3. /app/Providers/AuthServicePorvider.php**
```
use Laravel\Passport\Passport;
```
and
```
Passport::routes(); //in boot()
```

**4. /config/auth.php**
```
'api' => [
    'driver' => 'passport',
    'provider' => 'users',
],
```

**5. /config/services.php**
```
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_CLIENT_REDIRECT')
],

'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_CLIENT_REDIRECT')
]
```

Now you can serve the application and hit the route ```/social-login``` with ```provider``` (e.g. facebook, google) and ```access_token``` (Access token retrieved from social service providers)

## License
Licensed under the MIT License

## Author
Pimple Suhsant (https://pimplesushant.in)
