<?php
use Immap\Watchkeeper\Repositories\Interfaces\RoleRepositoryInterface as RoleRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
class RoleController extends BaseController {

    protected $roleRepo;
    protected $permissionRepo;
    function __construct(RoleRepositoryInterface $roleRepo,PermissionRepositoryInterface $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
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
        $roles = $this->roleRepo->getPaginated(compact('sort','direction'));
        return View::make('admin.roles.index',compact('roles'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.roles.create');
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
            $data = Input::only(array('name','display_name'));
            $this->roleRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.roles.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.roles.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $role = $this->roleRepo->byId($id);
        if ( is_null($id) || is_null($role) )
        {
            return Redirect::route('admin.roles.create');
        }
        else
        {
            return View::make('admin.roles.edit',compact('role'));
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
            $data = Input::only(array('name','display_name'));
            $data['id'] = $id;
            $this->roleRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.roles.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPermissions($id = null)
    {
        try
        {
            if ($id !== null)
            {
                $role = $this->roleRepo->byIdWithPermissions($id);
            }
            else
            {
                $role = $this->roleRepo->getLast();
            }
            $roles = $this->roleRepo->orderBy(array( 'sort' => 'display_name', 'direction' => 'ASC'));
            $permissions = $this->permissionRepo->getModel()->orderBy('group_name', 'ASC')
                                                            ->orderBy('display_name', 'ASC')
                                                            ->get();
            return View::make('admin.roles.role-permissions',compact('roles','role','permissions'));
        }
        catch (ModelNotFoundException $e)
        {

        }
    }

    public function postAttachPermissions($id)
    {
        try
        {
            $input = Input::only('roles','permissions');
            $role = $this->roleRepo->byIdWithPermissions($id);
            $permissions = array();
            if (!empty($input['permissions'])) {
                foreach ($input['permissions'] as $key => $value) {
                    $permissions[] = (int)$value;
                }
            }
            $this->roleRepo->savePermissions($role->id, $permissions);
            Cache::flush();
            return Redirect::route('admin.roles.get.permissions',$role->id)
                    ->with('message', 'Save Successfully');;
        }
        catch (ModelNotFoundException $e)
        {

        }
    }

}
