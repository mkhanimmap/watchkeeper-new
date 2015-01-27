<?php namespace Immap\Watchkeeper\Facades;

/**
 * @see \Immap\Watchkeeper\Pagination\JQMPaginator
 */
class JQMPaginator extends \Illuminate\Support\Facades\Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'jqmpaginator'; }

}
