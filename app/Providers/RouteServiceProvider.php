<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

use App\Models\Post;
use App\Models\Tab;
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
    public const HOME = '/dashboard';
    public const LANDING = '/';

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

        Route::bind('postSeoName', function($value){
            // Replace dashes with spaces and search the corresponding post (unique name, no special chars)
            $searchTerm = str_replace('-', ' ', $value);
            // check user permissions if any
            $userIsAdmin = false;
            if(Auth::check()){
                $userIsAdmin = Auth::user()->user_type == User::ADMIN;
            }
            // WARNING: use ILIKE or LOWER in case the database changes
            // from mysql/mariadb tu any case sensitive engine as mysql
            // and mariadb are case insensitive
            $post = Post::where('name', $searchTerm)
                ->when(!$userIsAdmin, function($query, $userIsAdmin){
                    // only filter out non-public posts if the current user is not an admin
                    return $query->where('public', true);
                })
                ->firstOrFail();
            
            return $post;
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
