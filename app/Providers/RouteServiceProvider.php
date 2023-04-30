<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

use App\Models\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for ('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for ('register', function (Request $request) {
            if ($request->session()->has('register')) {
                $register = $request->session()->get('register');
                list($u_id, $app_id) = explode(':', $register);
                $limit = Limit::perMinute(5)->by($u_id);
                if ($request->session()->has('token')) {
                    $limit = $limit->response(function () use ($request) {
                        return response()->json(['token' => $request->session()->get('token')], 200);
                    });
                } else {
                    $user = User::where('u_id', $u_id)->where('app_id', $app_id)->first();
                    if ($user && $user->token) {
                        $limit = $limit->response(function () use ($user) {
                            return response()->json(['token' => $user->token], 200);
                        });
                    }
                }
                return $limit;
            }
        });

        RateLimiter::for ('mock', function (Request $request) {
            $receipt = substr($request->receipt, -2);
            if (intval($receipt) % 6 == 0)
                return response()->json(['message' => 'Too many requests'], 429);
            else
                return Limit::perMinute(1000)->by($request->app_id);
        });
    }
}