<?php namespace Immap\Watchkeeper;

use Illuminate\Support\ServiceProvider as ServiceProvider;

class WatchKeeperServiceProvider extends ServiceProvider {
    /**
     * Register the service provider.
     * @returb void
     */
    public function register()
    {
        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbCountryRepository');
        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\UsergroupRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbUsergroupRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\UserRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbUserRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\RoleRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbRoleRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\PermissionRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbPermissionRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbClassificationRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbPointAreaRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\RiskRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbRiskRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\SecurityAdvisoryRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbSecurityAdvisoryRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\ThreatRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbThreatRepository');


        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\IncidentRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbIncidentRepository');

        $this->app->bind(
            'Immap\Watchkeeper\Repositories\Interfaces\PoiRepositoryInterface',
            'Immap\Watchkeeper\Repositories\DbPoiRepository');
    }
}
