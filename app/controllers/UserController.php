<?php
use Immap\Watchkeeper\Repositories\Interfaces\UserRepositoryInterface as UserRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\RoleRepositoryInterface as RoleRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class UserController extends \BaseController {
    protected $userRepo;
    protected $roleRepo;
    protected $countryRepo;
    function __construct(UserRepositoryInterface $userRepo,
       RoleRepositoryInterface $roleRepo,
       CountryRepositoryInterface $countryRepo)
    {
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
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
        $users = $this->userRepo->getPaginated(compact('sort','direction'));
        return View::make('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        return View::make('admin.users.create');
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
            $data = Input::only(array('username','email','firstname','lastname','middlename'));
            $this->userRepo->create($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.users.create')->withErrors($e->getErrors())->withInput(Input::except('password','password_confirmation'));
        }
        return Redirect::route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id = null)
    {
        return View::make('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id = null)
    {
        $user = $this->userRepo->byId($id);
        if ( is_null($id) || is_null($user) )
        {
            return Redirect::route('admin.users.create');
        }
        else
        {
            $usernameEncrypt = $this->userRepo->encryptUsername($user->username);
            $emailEncrypt = $this->userRepo->encryptEmail($user->email);
            return View::make('admin.users.edit',compact('user','usernameEncrypt','emailEncrypt'));
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
            $data = Input::only(array('username','email','firstname','lastname','middlename','status'));
            $data['id'] = $id;
            $this->userRepo->update($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('admin.users.edit',$id)->withErrors($e->getErrors())->withInput(Input::except('password'));
        }
        catch (ModelNotFoundException $e)
        {
        }
        return Redirect::route('admin.users.index');
    }

    public function getCountries($id = null)
    {
        try
        {
            if ($id !== null)
            {
                $user = $this->userRepo->getModel()->with('countries')->where('id','=',$id)->firstOrFail();
            }
            else
            {
                $user = $this->userRepo->getLast();
            }
            $users = $this->userRepo->getModel()->orderBy('firstname','asc')->get();
            $countries = $this->countryRepo->getCountry();
            return View::make('admin.users.user-country',compact('user','users','countries'));
        }
        catch (ModelNotFoundException $e)
        {
            return "Errors";
        }
    }
    public function getRoles($id = null)
    {
        try
        {
            if ($id !== null)
            {
                $user = $this->userRepo->byIdWithRoles($id);
            }
            else
            {
                $user = $this->userRepo->getLast();
            }
            $users = $this->userRepo->getModel()->orderBy('firstname','asc')->get();
            $roles = $this->roleRepo->getModel()->orderBy('display_name','asc')->get();
            return View::make('admin.users.user-role',compact('user','users','roles'));
        }
        catch (ModelNotFoundException $e)
        {
            return "Not Found";
        }
    }
    public function postAttachCountry($id)
    {
        try
        {
            $input = Input::only('countries','users');

            $user = $this->userRepo->byId($id);
            $countries = array();
            if (!empty($input['countries'])) {
                foreach ($input['countries'] as $key => $value) {
                    $countries[] = (int)$value;
                }
            }
            $this->userRepo->attachCountries($user->id, $countries);
            return Redirect::route('admin.users.get.country',$user->id)->with('message', 'Save Successfully');
        }
        catch (ModelNotFoundException $e)
        {
            return "Not Found";
        }
    }


    public function postAttachRole($id)
    {
        try
        {
            $input = Input::only('roles','users');

            $user = $this->userRepo->byId($id);
            $roles = array();
            if (!empty($input['roles'])) {
                foreach ($input['roles'] as $key => $value) {
                    $roles[] = (int)$value;
                }
            }
            $this->userRepo->attachRoles($user->id, $roles);
            Cache::flush();
            return Redirect::route('admin.users.get.role',$user->id)->with('message', 'Save Successfully');
        }
        catch (ModelNotFoundException $e)
        {
            return "Not Found";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = Immap\Watchkeeper\User::find($id);
        $username = $user->username;
        $user->delete();
        return Redirect::route('admin.users.index')->with('success', "$username has been deleted.");
    }
}
