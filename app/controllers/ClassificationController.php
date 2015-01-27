<?php

use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface as ClassificationRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
class ClassificationController extends BaseController {

    protected $classificationRepo;

    function __construct(ClassificationRepositoryInterface $classificationRepo)
    {
        $this->classificationRepo = $classificationRepo;
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
        $categoryId = Request::get('category');
        $callback = function($query) use($categoryId)
        {
            if (empty($categoryId) || ($categoryId === 0))
            {
                $query->orderBy('group_id');
            }
            else
            {
                $query->where('group_id',$categoryId);
            }
        };
        $classifications = $this->classificationRepo->getPaginated(compact('sort','direction','callback'));
        $classificationTypes = $this->classificationRepo->getAllClassificationType();
        return View::make('admin.classifications.index',compact('classifications','classificationTypes','categoryId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.classifications.create')
        ->with('classificationTypes',$this->classificationRepo->getAllClassificationType());
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
            $data = Input::only(array('name', 'code','group_id'));
            $this->classificationRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.classifications.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.classifications.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.classifications.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $classification = $this->classificationRepo->byId($id);
        if ( is_null($id) || is_null($classification) )
        {
            return Redirect::route('admin.classifications.create');
        }
        else
        {
            return View::make('admin.classifications.edit')->with('classification',$classification)
            ->with('classificationTypes',$this->classificationRepo->getAllClassificationType());
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
            $data = Input::only(array('name', 'code','group_id'));
            $data['id'] = $id;
            $this->classificationRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.classifications.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.classifications.index');
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
