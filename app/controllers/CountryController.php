<?php

use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
class CountryController extends BaseController {

    protected $countryRepo;
    public $inputSpec = array('name','code_a2','code_a3','active');
    protected $statusList = array(true => "Active",false => "Inactive");
    function __construct(CountryRepositoryInterface $countryRepo)
    {
        $this->countryRepo = $countryRepo;
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
        $countries = $this->countryRepo->getPaginated(compact('sort','direction'));
        return View::make('admin.countries.index',compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.countries.create',array("status_list" => $this->statusList));
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
            $data = Input::only($this->inputSpec);
            $this->countryRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.countries.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.countries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.countries.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $country = $this->countryRepo->byId($id);
        if ( is_null($id) || is_null($country) )
        {
            return Redirect::route('admin.countries.create');
        }
        else
        {
            return View::make('admin.countries.edit',array('country' => $country, "status_list" => $this->statusList));
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
            $data = Input::only($this->inputSpec);
            $data['id'] = $id;
            $this->countryRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.countries.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
            $this->countryRepo->deleteById($id);
            return Redirect::route('admin.countries.index');
        }
        catch (Illuminate\Database\QueryException $e)
        {
            return Redirect::route('admin.countries.index')->withErrors(array("error-msg" => "The selected country has been used in anothers form, Please removed it before."));
        }

    }

    /**
     * Change the specified country active from storage
     * @param int $id
     * @param string $status status must be either active or inactive
     * @return Response
     */
    public function getChangeStatus($id,$status='active')
    {
        try
        {
            $this->countryRepo->changeStatus($id,$status);
            return Redirect::route('admin.countries.index');
        }
        catch (Exception $e) //always ModelNotFoundException
        {
            return Redirect::route('admin.countries.index')->withErrors(array("error-msg" => "The selected country does not exits"));
        }
    }
}
