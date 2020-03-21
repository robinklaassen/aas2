<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Blade::if('role', function ($roles) {
			if (!is_array($roles)) {
				$roles = [$roles];
			}
			return Auth::user()->hasAnyRole($roles);
		});


		Blade::directive('canany', function ($expression) {
			list($capa, $cls, $entity) = array_map("trim", explode(",", $expression));
			$data = "<?php if (isset($entity) && \Auth::user()->can($capa, $entity) || \Auth::user()->can($capa . 'Any', $cls)): ?>";
			return $data;
		});

		Blade::directive('endcanany', function ($b) {
			return "<?php endif; ?>";
		});

		Validator::extend('same_when', function ($attribute, $value, $parameters, $validator) {
			$data = $validator->getData();
			if ($value !== $parameters[0]) {
				return true;
			} else {
				return isset($data[$parameters[1]]) && isset($data[$parameters[2]]) && $data[$parameters[1]] !== $data[$parameters[2]];
			}
		});
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}
}
