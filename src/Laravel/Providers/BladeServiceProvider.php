<?php

namespace bnjns\WebDevTools\Laravel\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('HelpDoc', function ($path) {
            return "<?php echo \Markdown::convertToHtml(file_get_contents(base_path('resources/documentation/' . str_replace('.', '/', {$path}) . '.md'))); ?>";
        });
        Blade::directive('InputError', function ($name) {
            return "<?php echo \$errors->any() && \$errors->default->has({$name}) ? ('<div class=\"invalid-feedback\">' . \$errors->default->first({$name}) . '</div>'): ''; ?>";
        });
        Blade::directive('Paginator', function ($name) {
            return "<?php echo get_class({$name}) == 'Illuminate\Pagination\LengthAwarePaginator' ? {$name} : ''; ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
