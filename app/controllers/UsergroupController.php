<?php
use Immap\Watchkeeper\Repositories\Interfaces\UsergroupRepositoryInterface as UsergroupRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
class UsergroupController extends \BaseController {

    protected $usergroupRepo;

    function __construct(UsergroupRepositoryInterface $usergroupRepo)
    {
        $this->usergroupRepo = $usergroupRepo;
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
        $usergroups = $this->usergroupRepo->getPaginated(compact('sort','direction'));
        return View::make('admin.usergroups.index',compact('usergroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.usergroups.create');
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
            $data = Input::only(array('name','code'));
            $this->usergroupRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.usergroups.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.usergroups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.usergroups.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $usergroup = $this->usergroupRepo->byId($id);
        if ( is_null($id) || is_null($usergroup) )
        {
            return Redirect::route('admin.usergroups.create');
        }
        else
        {
            return View::make('admin.usergroups.edit',compact('usergroup'));
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
            $data = Input::only(array('name','code'));
            $data['id'] = $id;
            $this->usergroupRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.usergroups.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.usergroups.index');
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

}
