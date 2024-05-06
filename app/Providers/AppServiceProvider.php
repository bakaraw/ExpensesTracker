<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\SafeSubmit\SafeSubmit;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('safesubmit', function ($expression){
            return "<?php echo '<input type=\"hidden\" name=\"'.app(SafeSubmit::class)->tokenKey().'\" value=\"'.app(SafeSubmit::class)->token().'\"> '; ?>";

        });

        Log::debug("directive: " . app(SafeSubmit::class)->token());
    }
}
