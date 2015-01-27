<?php

use Immap\Watchkeeper\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
class PermissionController  extends BaseController {

    protected $permissionRepo;

    function __construct(PermissionRepositoryInterface $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showIndex()
    {
        $sort = Request::get('sort');
        $direction = Request::get('direction');
        $permissions = $this->permissionRepo->getPaginated(compact('sort','direction'));
        return View::make('admin.permissions.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.permissions.create')->with('routes_list',get_all_index_routes());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore()
    {
        try
        {
            $data = Input::only(array('name','display_name','group_name','action_name'));
            $this->permissionRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.permissions.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.permissions.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.permissions.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $permission = $this->permissionRepo->byId($id);
        if ( is_null($id) || is_null($permission) )
        {
            return Redirect::route('admin.permissions.create');
        }
        else
        {
            $routes_list = get_all_index_routes();
            return View::make('admin.permissions.edit',compact('permission','routes_list'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postUpdate($id)
    {
        try
        {
            $data = Input::only(array('name','display_name','group_name','action_name'));
            $data['id'] = $id;
            $this->permissionRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.permissions.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destory($id)
    {
        try
        {
            $this->permissionRepo->deleteById($id);
            return Redirect::route('admin.permissions.index');
        }
        catch (Illuminate\Database\QueryException $e)
        {
            return Redirect::route('admin.permissions.index')->withErrors(array("error-msg" => "The selected permission has been assigned to role(s), Please removed role-permissions before."));
        }
    }
}
