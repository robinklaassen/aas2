<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Anonymize\AnimalNameGenerator;
use App\Services\Anonymize\AnonymizeGenerator;
use App\Services\Anonymize\AnonymizeGeneratorInterface;
use App\Services\Anonymize\AnonymizeParticipant;
use App\Services\Anonymize\AnonymizeParticipantInterface;
use App\Services\Anonymize\NameGeneratorInterface;
use App\Services\Chart\ChartServiceInterface;
use App\Services\Chart\LavachartsChartService;
use App\Services\Geocoder\GeocoderInterface;
use App\Services\Geocoder\PositionstackGeocoder;
use App\Services\ObjectManager\EloquentObjectManager;
use App\Services\ObjectManager\ObjectManagerInterface;
use App\Services\Recaptcha\GoogleRecaptchaV3Validator;
use App\Services\Recaptcha\RecaptchaValidator;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Blade::if('role', function ($roles) {
            if (! is_array($roles)) {
                $roles = [$roles];
            }
            return Auth::user()->hasAnyRole($roles);
        });

        Blade::directive('canany', function ($expression) {
            list($capa, $cls, $entity) = array_map('trim', explode(',', $expression));
            $data = "<?php if (isset(${entity}) && \Auth::user()->can(${capa}, ${entity}) || \Auth::user()->can(${capa} . 'Any', ${cls})): ?>";
            return $data;
        });

        Blade::directive('endcanany', function ($b) {
            return '<?php endif; ?>';
        });

        /**
         * Parameters are: $par_value, $par_fld1, $par_fld2
         * When validation value is the same the $par_value
         * The the data of the fields referenced in $par_fld1 and $par_fld2 should be different
         */
        Validator::extend('when_then_different', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            if ($value !== $parameters[0]) {
                return true;
            }
            return isset($data[$parameters[1]]) && isset($data[$parameters[2]]) && $data[$parameters[1]] !== $data[$parameters[2]];
        });

        Blade::directive('money', function ($amount) {
            return "<?php echo '&euro; ' .  number_format(${amount}, 2); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
        $this->app->bind(
            'App\Services\DeclarationService'
        );
        $this->app->bind(AnonymizeParticipantInterface::class, AnonymizeParticipant::class);
        $this->app->bind(AnonymizeGeneratorInterface::class, AnonymizeGenerator::class);
        $this->app->when(AnonymizeGenerator::class)->needs(NameGeneratorInterface::class)->give(AnimalNameGenerator::class);
        $this->app->bind(ObjectManagerInterface::class, EloquentObjectManager::class);
        $this->app->bind(GeocoderInterface::class, PositionstackGeocoder::class);
        $this->app->bind(ChartServiceInterface::class, LavachartsChartService::class);
        $this->app->bind(RecaptchaValidator::class, function (Application $app) {
            return new GoogleRecaptchaV3Validator(new Client(), config('recaptcha.secret'));
        });
    }
}
