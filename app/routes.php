<?php
Event::listen('illuminate.query',function($query)
{
    //d($query);
});

Route::when('*', 'csrf', array('POST', 'PUT', 'PATCH', 'DELETE'));
Route::when('sec*', 'manage_menus');

Route::filter('manage_menus','Immap\Watchkeeper\Filters\AuthFilter@manageMenus');
Route::filter('manage_permissions','Immap\Watchkeeper\Filters\AuthFilter@managePermissions');


Route::pattern('id', '[0-9]+');
Route::pattern('name','[a-z]+');

Route::get('/change-role/{userId}/{roleId}',function($userId,$roleId) {
    Session::put('current_role_id', $roleId);
    Cache::forget('current_menu_'.Session::get('user_id'));
    Cache::forget('current_group_prefix_'.Session::get('user_id'));
    return Redirect::route('admin.main');
})->where(array('userId' => '[0-9]+', 'roleId' => '[0-9]+'));


Route::get('/', array('as' => 'home.index.', 'uses' => "HomeController@showHome"));
Route::get('/home',array('as' => 'home', 'uses' => "HomeController@showHome"));


Route::get('login',array('as' => 'auth.login', 'uses' => "AuthController@showLogin"));
Route::post('login',array('as' => 'auth.login.post', 'uses'  => 'AuthController@postLogin'));
Route::get('logout', array('as' => 'auth.logout', 'uses'  => 'AuthController@getLogout'));
Route::get('sec/logout', array('as' => 'auth.sec.logout', 'uses'  => 'AuthController@getSecLogout'));


Route::group(array('prefix' => 'admin','before' => array('manage_menus' , 'manage_permissions')), function()
{
    Route::get('/',array('as' => 'admin.main', 'uses' => function()
    {
        return View::make('admin.main');
    }));

    Route::group(array('prefix' => 'countries'), function()
    {
        Route::get('/', array('as' => 'admin.countries.index',  'uses' => 'CountryController@showIndex'));
        Route::get('/create', array('as' => 'admin.countries.create',  'uses' => 'CountryController@getCreate'));
        Route::post('/store', array('as' => 'admin.countries.store',  'uses' => 'CountryController@postStore'));
        Route::get('/{id}/edit', array('as' => 'admin.countries.edit',  'uses' => 'CountryController@getEdit'));
        Route::post('/{id}/update', array('as' => 'admin.countries.update',  'uses' => 'CountryController@postUpdate'));
        Route::get('/{id}/change-status/{status}', array('as' => 'admin.countries.changestatus',  'uses' => 'CountryController@getChangeStatus'));
        Route::get('/{id}', array('as' => 'admin.countries.show',  'uses' => 'CountryController@getShow'));
        Route::get('/{id?}/destory', array('as' => 'admin.countries.destory',  'uses' => 'CountryController@destroy'));
    });

    Route::group(array('prefix' => 'pointareas'), function()
    {
        Route::get('/', array('as' => 'admin.pointareas.index',  'uses' => 'PointAreaController@showIndex'));
        Route::get('/create', array('as' => 'admin.pointareas.create',  'uses' => 'PointAreaController@getCreate'));
        Route::post('/store', array('as' => 'admin.pointareas.store',  'uses' => 'PointAreaController@postStore'));
        Route::get('/{id}/edit', array('as' => 'admin.pointareas.edit',  'uses' => 'PointAreaController@getEdit'));
        Route::post('/{id}/update', array('as' => 'admin.pointareas.update',  'uses' => 'PointAreaController@postUpdate'));
        Route::get('/{id}', array('as' => 'admin.pointareas.show',  'uses' => 'PointAreaController@getShow'));
        Route::get('/{id?}/destory', array('as' => 'admin.pointareas.destory',  'uses' => 'PointAreaController@destroy'));
    });

    Route::group(array('prefix' => 'classifications'), function()
    {
        Route::get('/', array('as' => 'admin.classifications.index',  'uses' => 'ClassificationController@showIndex'));
        Route::get('/create', array('as' => 'admin.classifications.create',  'uses' => 'ClassificationController@getCreate'));
        Route::post('/store', array('as' => 'admin.classifications.store',  'uses' => 'ClassificationController@postStore'));
        Route::get('/{id}/edit', array('as' => 'admin.classifications.edit',  'uses' => 'ClassificationController@getEdit'));
        Route::post('/{id}/update', array('as' => 'admin.classifications.update',  'uses' => 'ClassificationController@postUpdate'));
        Route::get('/{id}', array('as' => 'admin.classifications.show',  'uses' => 'ClassificationController@getShow'));
        Route::get('/{id?}/destory', array('as' => 'admin.classifications.destory',  'uses' => 'ClassificationController@destroy'));
    });

    Route::group(array('prefix' => 'roles'), function()
    {
        Route::get('/', array('as' => 'admin.roles.index',  'uses' => 'RoleController@showIndex'));
        Route::get('/create', array('as' => 'admin.roles.create',  'uses' => 'RoleController@getCreate'));
        Route::post('/store', array('as' => 'admin.roles.store',  'uses' => 'RoleController@postStore'));
        Route::get('/{id}/edit', array('as' => 'admin.roles.edit',  'uses' => 'RoleController@getEdit'));
        Route::post('/{id}/update', array('as' => 'admin.roles.update',  'uses' => 'RoleController@postUpdate'));
        Route::get('/{id}/get/permissions', array('as' => 'admin.roles.get.permissions',  'uses' => 'RoleController@getPermissions'));
        Route::get('/get/permissions', array('as' => 'admin.roles.permissions',  'uses' => 'RoleController@getPermissions'));
        Route::post('/{id}/attach/permissions', array('as' => 'admin.roles.attach.permissions',  'uses' => 'RoleController@postAttachPermissions'));
        Route::get('/{id}', array('as' => 'admin.roles.show',  'uses' => 'RoleController@getShow'));
        Route::get('/{id?}/destory', array('as' => 'admin.roles.destory',  'uses' => 'RoleController@destroy'));
    });

Route::group(array('prefix' => 'permissions'), function()
{
    Route::get('/', array('as' => 'admin.permissions.index',  'uses' => 'PermissionController@showIndex'));
    Route::get('/create', array('as' => 'admin.permissions.create',  'uses' => 'PermissionController@getCreate'));
    Route::post('/store', array('as' => 'admin.permissions.store',  'uses' => 'PermissionController@postStore'));
    Route::get('/{id}/edit', array('as' => 'admin.permissions.edit',  'uses' => 'PermissionController@getEdit'));
    Route::post('/{id}/update', array('as' => 'admin.permissions.update',  'uses' => 'PermissionController@postUpdate'));
    Route::get('/{id}', array('as' => 'admin.permissions.show',  'uses' => 'PermissionController@getShow'));
    Route::get('/{id}/destory', array('as' => 'admin.permissions.destory',  'uses' => 'PermissionController@destory'));
});

Route::group(array('prefix' => 'usergroups'), function()
{
    Route::get('/', array('as' => 'admin.usergroups.index',  'uses' => 'UsergroupController@showIndex'));
    Route::get('/create', array('as' => 'admin.usergroups.create',  'uses' => 'UsergroupController@getCreate'));
    Route::post('/store', array('as' => 'admin.usergroups.store',  'uses' => 'UsergroupController@postStore'));
    Route::get('/{id}/edit', array('as' => 'admin.usergroups.edit',  'uses' => 'UsergroupController@getEdit'));
    Route::post('/{id?}/update', array('as' => 'admin.usergroups.update',  'uses' => 'UsergroupController@postUpdate'));
    Route::get('/{id}', array('as' => 'admin.usergroups.show',  'uses' => 'UsergroupController@getShow'));
});

Route::group(array('prefix' => 'users'), function()
{
    Route::get('/', array('as' => 'admin.users.index',  'uses' => 'UserController@showIndex'));
    Route::get('/create', array('as' => 'admin.users.create',  'uses' => 'UserController@getCreate'));
    Route::post('/store', array('as' => 'admin.users.store',  'uses' => 'UserController@postStore'));
    Route::get('/{id}/edit', array('as' => 'admin.users.edit',  'uses' => 'UserController@getEdit'));
    Route::post('/{id?}/update', array('as' => 'admin.users.update',  'uses' => 'UserController@postUpdate'));
    Route::get('/{id}', array('as' => 'admin.users.show',  'uses' => 'UserController@getShow'));
    Route::get('/{id}/get/role', array('as' => 'admin.users.get.role',  'uses' => 'UserController@getRoles'));
    Route::get('/get/role', array('as' => 'admin.users.role',  'uses' => 'UserController@getRoles'));
    Route::get('/{id}/get/country', array('as' => 'admin.users.get.country',  'uses' => 'UserController@getCountries'));
    Route::get('/get/country', array('as' => 'admin.users.country',  'uses' => 'UserController@getCountries'));
    Route::post('/{id}/attach/country/', array('as' => 'admin.users.attach.countries',  'uses' => 'UserController@postAttachCountry'));
    Route::post('/{id}/attach/role/', array('as' => 'admin.users.attach.permissions',  'uses' => 'UserController@postAttachRole'));
    Route::get('/{id}/destory', array('as' => 'admin.users.destory',  'uses' => 'UserController@destroy'));
});

});

Route::group(array('prefix' => 'secm'), function()
{
    Route::get("/", array('as' => 'secm.index',  'uses' => 'SecMobileController@showIndex'));

    Route::group(array('prefix' => 'ina'), function()
    {
        Route::get("/", array('as' => 'secm.ina.index',  'uses' => 'IncidentController@showIndex'));
        Route::get("/page/{page?}", array('as' => 'secm.ina.index.page',  'uses' => 'IncidentController@showIndex'));
        Route::post('/store', array('as' => 'secm.ina.store',  'uses' => 'IncidentController@postStore'));
        Route::get('/create', array('as' => 'secm.ina.create',  'uses' => 'IncidentController@getCreate'));
        Route::get('/{id?}/edit', array('as' => 'secm.ina.edit',  'uses' => 'IncidentController@getEdit'));
        Route::post('/{id}/update', array('as' => 'secm.ina.update',  'uses' => 'IncidentController@postUpdate'));
        Route::get('/{id}/destroy', array('as' => 'secm.ina.destroy',  'uses' => 'IncidentController@getDestroy'));
    });

    Route::group(array('prefix' => 'poi'), function()
    {
        Route::get("/", array('as' => 'secm.poi.index',  'uses' => 'PoiController@showIndex'));
        Route::get("/page/{page?}", array('as' => 'secm.poi.index.page',  'uses' => 'PoiController@showIndex'));
        Route::post('/store', array('as' => 'secm.poi.store',  'uses' => 'PoiController@postStore'));
        Route::get('/create', array('as' => 'secm.poi.create',  'uses' => 'PoiController@getCreate'));
        Route::get('/{id?}/edit', array('as' => 'secm.poi.edit',  'uses' => 'PoiController@getEdit'));
        Route::post('/{id}/update', array('as' => 'secm.poi.update',  'uses' => 'PoiController@postUpdate'));
        Route::get('/{id}/destroy', array('as' => 'secm.poi.destroy',  'uses' => 'PoiController@getDestroy'));
    });

    Route::group(array('prefix' => 'risks'), function()
    {
        Route::get("/", array('as' => 'secm.risk.index',  'uses' => 'RiskController@showIndex'));
        Route::get("/page/{page?}", array('as' => 'secm.risk.index.page',  'uses' => 'RiskController@showIndex'));
        Route::post('/store', array('as' => 'secm.risk.store',  'uses' => 'RiskController@postStore'));
        Route::get('/create', array('as' => 'secm.risk.create',  'uses' => 'RiskController@getCreate'));
        Route::get('/{id?}/edit', array('as' => 'secm.risk.edit',  'uses' => 'RiskController@getEdit'));
        Route::post('/{id}/update', array('as' => 'secm.risk.update',  'uses' => 'RiskController@postUpdate'));
        Route::get('/{id}/destroy', array('as' => 'secm.risk.destroy',  'uses' => 'RiskController@getDestroy'));
    });

    Route::group(array('prefix' => 'isa'), function()
    {
        Route::get("/", array('as' => 'secm.security-advisory.index',  'uses' => 'SecurityAdvisoryController@showIndex'));
        Route::get("/page/{page?}", array('as' => 'secm.security-advisory.index.page',  'uses' => 'RiskController@showIndex'));
        Route::post('/store', array('as' => 'secm.security-advisory.store',  'uses' => 'SecurityAdvisoryController@postStore'));
        Route::get('/create', array('as' => 'secm.security-advisory.create',  'uses' => 'SecurityAdvisoryController@getCreate'));
        Route::get('/{id?}/edit', array('as' => 'secm.security-advisory.edit',  'uses' => 'SecurityAdvisoryController@getEdit'));
        Route::post('/{id}/update', array('as' => 'secm.security-advisory.update',  'uses' => 'SecurityAdvisoryController@postUpdate'));
        Route::get('/{id}/destroy', array('as' => 'secm.security-advisory.destroy',  'uses' => 'SecurityAdvisoryController@getDestroy'));
    });

    Route::group(array('prefix' => 'threat'), function()
    {
        Route::get("/", array('as' => 'secm.threat.index',  'uses' => 'ThreatController@showIndex'));
        Route::get("/page/{page?}", array('as' => 'secm.threat.index.page',  'uses' => 'ThreatController@showIndex'));
        Route::post('/store', array('as' => 'secm.threat.store',  'uses' => 'ThreatController@postStore'));
        Route::get('/create', array('as' => 'secm.threat.create',  'uses' => 'ThreatController@getCreate'));
        Route::get('/{id?}/edit', array('as' => 'secm.threat.edit',  'uses' => 'ThreatController@getEdit'));
        Route::post('/{id}/update', array('as' => 'secm.threat.update',  'uses' => 'ThreatController@postUpdate'));
        Route::get('/{id}/destroy', array('as' => 'secm.threat.destroy',  'uses' => 'ThreatController@getDestroy'));
    });

});

Route::controller('reminders', 'RemindersController');

Route::get('testsendmail/{data?}',function($data = null)
{
    $data = array();
    Mail::queue('emails.welcome', $data, function($message)
    {
        $message->from('paepod@gmail.com','Pae Admin');
        $message->to('paepod@facebook.com', 'Pae Shinnawat')->subject('Welcome!');
    });
    return "<h1>Ok</h1>";
});
