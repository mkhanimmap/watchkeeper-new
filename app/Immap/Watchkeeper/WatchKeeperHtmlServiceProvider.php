<?php namespace Immap\Watchkeeper;

use Immap\Watchkeeper\Support\FormBuilder\WKFormBuilder as WKFormBuilder;

class WatchKeeperHtmlServiceProvider extends \Illuminate\Html\HtmlServiceProvider {
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app['form'] = $this->app->share(function($app)
        {
            $form = new WKFormBuilder($app['html'], $app['url'], $app['session.store']->getToken(), $app['translator']);
            return $form->setSessionStore($app['session.store']);
        });
    }
}
