<?php

namespace bnjns\WebDevTools\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        Blade::directive('HelpDoc', function ($path) {
            return "<?php echo \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(file_get_contents(base_path('resources/documentation/' . str_replace('.', '/', {$path}) . '.md'))); ?>";
        });
        Blade::directive('InputClass', function ($name) {
            return "<?php echo \$errors->any() ? (\$errors->default->has({$name}) ? 'has-error' : 'has-success') : ''; ?>";
        });
        Blade::directive('InputError', function ($name) {
            return "<?php echo \$errors->any() && \$errors->default->has({$name}) ? ('<p class=\"help-block\">' . \$errors->default->first({$name}) . '</p>') : ''; ?>";
        });
    }
    
    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        //
    }
}
