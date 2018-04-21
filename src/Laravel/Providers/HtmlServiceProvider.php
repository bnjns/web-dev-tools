<?php

namespace bnjns\WebDevTools\Laravel\Providers;

use bnjns\WebDevTools\Laravel\Html\FormBuilder;
use Collective\Html\HtmlServiceProvider as CollectiveHtmlProvider;

class HtmlServiceProvider extends CollectiveHtmlProvider
{
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->token());

            return $form->setSessionStore($app['session.store']);
        });
    }
}
