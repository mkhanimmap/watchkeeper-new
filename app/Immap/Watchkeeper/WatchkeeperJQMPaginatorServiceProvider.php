<?php namespace Immap\Watchkeeper;
use Illuminate\Support\ServiceProvider as ServiceProvider;
use Immap\Watchkeeper\Support\Pagination\JQMPaginator as JQMPaginator;
class WatchkeeperJQMPaginatorServiceProvider  extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('jqmpaginator', function($app)
        {
            $paginator = new JQMPaginator($app['html']);
            return $paginator;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('jqmpaginator');
    }
}
