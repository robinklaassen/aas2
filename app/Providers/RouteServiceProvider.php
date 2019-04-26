<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		// Route model bindings
		Route::model('participants', 'App\Participant');
		Route::model('members', 'App\Member');
		Route::model('events', 'App\Event');
		Route::model('locations', 'App\Location');
		Route::model('courses', 'App\Course');
		Route::model('actions', 'App\Action');
		Route::model('declarations', 'App\Declaration');
		Route::model('users', 'App\User');
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
