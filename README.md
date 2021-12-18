# Trakker Official Laravel Client

Trakker is a simple and easy to use website analytics service that supports server side logging. To learn more, visit [trakker.techies.africa](https://trakker.techies.africa/)

## Getting Started

First things first, create an app on [Trakker](https://trakker.techies.africa/) to obtain your APP_KEY and APP_SECRET.

## Installation

```bash
composer require techies-africa/laravel_trakker
```

Next, add the service provider to your app/config.php file under the list of providers.

```
'providers' => [
    ...,
    App\Providers\RouteServiceProvider::class,
    TechiesAfrica\LaravelTrakker\Providers\TrakkerServiceProvider::class,
    ...
],
```

Finally, add the middleware to your app\Http\Kernel.php file under the $middleware array. You can add it under any array that best suites your needs.

```
protected $middleware = [
     ...,
     \Illuminate\Session\Middleware\StartSession::class,
     TechiesAfrica\LaravelTrakker\Middlewares\TrakkerMiddleware::class,
     ...
];
```

Setup complete. Now you need to add your keys to your .env file.

```

TRAKKER_BASE_URL="https://trakker.techies.africa"
TRAKKER_APP_KEY="*************"
TRAKKER_APP_SECRET="********"
TRAKKER_LOG_CHANNEL="stack"

```

If you wish to log errors in a seperate file other than the default laravel.log file, then, add this to array of channels in the app/logging.php file.

```
 'trakker' => [
     'driver' => 'single',
     'path' => storage_path('logs/trakker.log'),
     'level' => 'debug',
 ],

```

## Usage

Once installation is complete , you should be able to see your activities on your app`s dashboard. You can customize the configurations as you see fit.

This is what the TrakkerMiddleware looks like. If you would like to implement it differently , do well to create a middleware , copy the content of the handle method and remember to register your new middleware in your Kernel file.

```php
<?php

namespace TechiesAfrica\LaravelTrakker\Middlewares;

use TechiesAfrica\LaravelTrakker\Services\TrakkerService;
use Closure;
use Illuminate\Http\Request;

class TrakkerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tracker = TrakkerService::tracker();
        // $tracker->setIgnoreRoutes([
        //     "web.read_file"
        // ]);
        // $tracker->setAuthenticatedMiddlewares(["auth" , "admin" , "verified"]);
        // $tracker->setIgnoreMiddlewares(["Barryvdh\Debugbar\Middleware\DebugbarEnabled"]);
        // $tracker->setUserFields(["id" => "id", "name" => "full_name", "email" => "email"]);
        // $tracker->setShouldLog(false);
        $tracker->preRequest($request);

        $response =  $next($request);

        // TrakkerService::log("pushing data");
        $tracker->postRequest($request)
            ->push();

        return $response;
    }
}

```

The TrakkerService::tracker(); instance can be accessed from anywhere in your application and can be used to modify the initial values set in the middleware.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
