<?php namespace Immap\Watchkeeper\Repositories\Interfaces;

interface CountryRepositoryInterface extends GenericRepositoryInterface {
    function changeStatus($id, $status);
    function getCountry($includeInactive);
}
