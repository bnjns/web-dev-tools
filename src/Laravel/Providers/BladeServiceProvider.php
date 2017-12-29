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
        Blade::directive('Paginator', function ($arguments) {
            list($name, $style) = array_pad($this->getDirectiveArguments($arguments), 2, null);
            return "<?php echo get_class({$name}) == 'Illuminate\Pagination\LengthAwarePaginator' ? {$name}->render('pagination::" . ($style ?: 'default') . "') : ''; ?>";
        });
        Blade::directive('ContentWidth', function () {
            return "<?php echo !empty(trim(\$__env->yieldContent('content-width'))) ? ('w-' . \$__env->yieldContent('content-width')) : ''; ?>";
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

    /**
     * Convert a string of arguments to an array.
     *
     * @param string $arguments
     *
     * @return array
     */
    private function getDirectiveArguments($arguments)
    {
        return explode(',', str_replace(['(', ')', ' ', "'"], '', $arguments));
    }
}
