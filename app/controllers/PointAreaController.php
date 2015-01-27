<?php

use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface as PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
class PointAreaController  extends BaseController {

    protected $pointareaRepo;

    function __construct(PointAreaRepositoryInterface $pointareaRepo)
    {
        $this->pointareaRepo = $pointareaRepo;
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
        $pointareas = $this->pointareaRepo->getPaginated(compact('sort','direction'));
        return View::make('admin.pointareas.index',compact('pointareas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.pointareas.create');
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
            $data = Input::only(array('name','description','geoJSON'));
            $this->pointareaRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.pointareas.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.pointareas.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.pointareas.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $pointarea = $this->pointareaRepo->byId($id);
        if ( is_null($id) || is_null($pointarea) )
        {
            return Redirect::route('admin.pointareas.create');
        }
        else
        {
            return View::make('admin.pointareas.edit',compact('pointarea'));
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
            $data = Input::only(array('name','description','geoJSON'));
            $data['id'] = $id;
            $this->pointareaRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.pointareas.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.pointareas.index');
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
            $data['id'] = $id;
            //$this->pointareaRepo->deleteById($id);
        }
        catch (ValidationException $e)
        {

        }
        return Redirect::route('admin.pointareas.index');
    }

}
