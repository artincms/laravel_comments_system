<?php

namespace ArtinCMS\LCS;

use Illuminate\Support\ServiceProvider;

class LCSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
    	// the main router
	    include_once __DIR__.'/Routes/backend_lcs_route.php';
	    include_once __DIR__.'/Routes/frontend_lcs_route.php';
	    // the main views folder
	    $this->loadViewsFrom(__DIR__ . '/Views', 'laravel_comments_system');
	    // the main migration folder for create sms_ir tables

	    // for publish the views into main app
	    $this->publishes([
		    __DIR__ . '/Views' => resource_path('views/vendor/laravel_comments_system'),
	    ]);

	    $this->publishes([
		    __DIR__ . '/Database/Migrations/' => database_path('migrations')
	    ], 'migrations');

	    // for publish the assets files into main app
	    $this->publishes([
		    __DIR__.'/assets' => public_path('vendor/laravel_comments_system'),
	    ], 'public');

	    // for publish the sms_ir config file to the main app config folder
	    $this->publishes([
		    __DIR__ . '/Config/LCS.php' => config_path('laravel_comments_system.php'),
	    ]);
        $this->publishes([
            __DIR__ . '/Traits/LaravelCommentSystem.php' => app_path('Traits/LaravelCommentSystem.php'),
        ]);
        $this->publishes([
            __DIR__ . '/Components' => resource_path('assets/js/components/laravel_comment_system'),
        ]);
        // publish language
        $this->publishes([
            __DIR__ . '/Lang/En/commentBackend.php' => resource_path('lang/en/commentBackend.php'),
        ]);
        $this->publishes([
            __DIR__ . '/Lang/FA/lcs_fronted.php' => resource_path('lang/fa/lcs_fronted.php'),
        ]);
        $this->publishes([
            __DIR__ . '/Lang/En/lcs_fronted.php' => resource_path('lang/en/lcs_fronted.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	// set the main config file
	    $this->mergeConfigFrom(
		    __DIR__ . '/Config/LCS.php', 'laravel_comments_system'
	    );

		// bind the LCSC Facade
	    $this->app->bind('LCSC', function () {
		    return new LCSC;
	    });
    }
}
