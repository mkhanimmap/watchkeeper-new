<?php
/**
 * Bootstrap file for (re)creating database before running tests
 *
 * You only need to put this file in "bootstrap" directory of the project
 * and change "bootstrap" phpunit parameter within "phpunit.xml"
 * from "bootstrap/autoload.php" to "bootstap/testing.php"
 */

$testEnvironment = 'testing';

$config = require("app/config/{$testEnvironment}/database.php");

extract($config['connections'][$config['default']]);

/*
$connection = new PDO("{$driver}:user={$username} password={$password}");
$connection->query("DROP DATABASE IF EXISTS ".$database);
$connection->query("CREATE DATABASE ".$database);

require_once('app/libraries/helpers.php');
*/
// run migrations for packages
foreach(glob('vendor/*/*', GLOB_ONLYDIR) as $package) {
    $packageName = substr($package, 7); // drop "vendor" prefix
    passthru("./artisan migrate --package={$packageName} --env={$testEnvironment}");
}
passthru('./artisan migrate --env='.$testEnvironment);

require('autoload.php'); // run laravel's original bootstap file
