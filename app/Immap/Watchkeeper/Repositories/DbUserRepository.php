<?php namespace Immap\Watchkeeper\Repositories;
/**
 * adapt from Zizaco/entrust https://github.com/Zizaco/entrust
 */
use Immap\Watchkeeper\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\UserValidator as UserValidator;
use Immap\Watchkeeper\User as User;
use Illuminate\Support\Facades\Event as Event;
use Illuminate\Support\Facades\Crypt as Crypt;
use Immap\Watchkeeper\Services\EmailSenders\RegistrationEmailSender as RegistrationEmailSender;
class DbUserRepository extends AbstractDbRepository implements UserRepositoryInterface {

    protected $defaultOrderBy = "username";
    protected $passwordLength = 5;
    private $user;
    function __construct(User $model)
    {
        $this->model = $model;
        Event::listen('user.saving','Immap\Watchkeeper\Services\Validators\UserValidator@onSave');
        Event::listen('user.created','Immap\Watchkeeper\Services\EmailSenders\RegistrationEmailSender@sendWelcome');
    }

    function byUsername($username)
    {
        return $this->model->where('username', '=', $username)->firstOrFail();
    }

    function byIdUsernameAndEmail($id, $username ,$email)
    {
        return $this->model->where('id','=',$id)
                                    ->where('username','=', $username)
                                    ->where('email','=', $email)
                                    ->firstOrFail();
    }

    function getLast()
    {
        return $this->model->orderby('id','desc')->firstOrFail();
    }

    function byIdWithRoles($id)
    {
        return $this->model->with('roles')->where('id',$id)->firstOrFail();
    }

    public function create(array $data)
    {
        Event::fire('user.saving', array($data));
        $this->user = new $this->model();
        $this->user->username = $data['username'];
        $this->user->email = $data['email'];
        $password = $this->makePassword();
        $data['password'] = $password;
        $this->user->password = $data['password'];
        $this->user->firstname = $data['firstname'];
        $this->user->lastname = $data['lastname'];
        $this->user->middlename = $data['middlename'];
        $this->user->active = true;
        $user = $this->user;
        Event::fire('user.created',compact('user','password'));
        return $this->user->save();
    }

    public function update(array $data)
    {
        $data['username'] = $this->decryptUsername($data['username']);
        $data['email'] = $this->decryptEmail($data['email']);
        Event::fire('user.saving', array($data));
        $this->user = $this->byIdUsernameAndEmail($data['id'], $data['username'], $data['email']);

        $this->user->firstname = $data['firstname'];
        $this->user->lastname = $data['lastname'];
        $this->user->middlename = $data['middlename'];
        if (!empty($data['status']) && $data['status'] === 'active')
        {
            $this->user->active = true;
        }
        else if (!empty($data['status']) &&  $data['status'] === 'inactive')
        {
            $this->user->active = false;
        }
        return $this->user->save();
    }

    public function save(array $data)
    {
        if ( isset($data['id']) && !empty($data['id']) )
        {
            return $this->update($data);
        }
        else
        {
            return $this->create($data);
        }
    }

    public function makePassword()
    {
        $pool = '!@#$%^&*()_+0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $this->passwordLength);
    }

    public function encryptUsername($username)
    {
        return Crypt::encrypt($username . "|" . rand());
    }

    public function encryptEmail($email)
    {
        return Crypt::encrypt($email . "|" . rand());
    }

    public function decryptUsername($username)
    {
        list($username,$rand) = explode('|',Crypt::decrypt($username));
        return $username;
    }
    public function decryptEmail($email)
    {
        list($email,$rand) = explode('|',Crypt::decrypt($email));
        return $email;
    }

    public function attachRoles($userId, $roles)
    {
        $this->user = $this->model->findOrFail($userId);
        return $this->user->roles()->sync($roles);
    }

    public function attachCountries($userId, $countries)
    {
        $this->user = $this->model->findOrFail($userId);
        return $this->user->countries()->sync($countries);
    }

    public function detachRoles($userId, $roles)
    {
        $this->user = $this->model->findOrFail($userId);
        return $this->user->roles()->detach($roles);
    }


    /**
     * Checks if the user has a Role by its name
     *
     * @param string $name Role name.
     *
     * @access public
     *
     * @return boolean
     */
    public function hasRole( $name )
    {
         foreach ($this->model->roles as $role)
         {
             if( $role->name == $name )
             {
                return true;
             }
         }

        return false;
    }


    /**
     * Check if user has a permission by its name
     *
     * @param string $permission Permission string.
     *
     * @access public
     *
     * @return boolean
     */
    public function can( $permission )
    {
         foreach ($this->model->roles as $role) {
            // Deprecated permission value within the role table.
             if( is_array($role->permissions) && in_array($permission, $role->permissions) )
             {
                return true;
             }

            // Validate against the Permission table
             foreach($role->perms as $perm)
             {
                 if($perm->name == $permission)
                 {
                    return true;
                 }
             }
         }

        return false;
    }

    /**
     * Checks role(s) and permission(s) and returns bool, array or both
     * @param string|array $roles Array of roles or comma separated string
     * @param string|array $permissions Array of permissions or comma separated string.
     * @param array $options validate_all (true|false) or return_type (boolean|array|both) Default: false | boolean
     * @return array|bool
     * @throws InvalidArgumentException
     */
    public function ability( $roles, $permissions, $options=array() )
    {
         // Convert string to array if that's what is passed in.
         if(!is_array($roles))
         {
             $roles = explode(',', $roles);
         }

         if(!is_array($permissions)){
            $permissions = explode(',', $permissions);
         }



         // Set up default values and validate options.
         if(!isset($options['validate_all']))
         {
            $options['validate_all'] = false;
         }
         else
         {
            if($options['validate_all'] != true && $options['validate_all'] != false)
            {
                throw new InvalidArgumentException();
            }
         }
         if(!isset($options['return_type']))
         {
            $options['return_type'] = 'boolean';
         }
         else
         {
            if($options['return_type'] != 'boolean' &&
                $options['return_type'] != 'array' &&
                $options['return_type'] != 'both')
            {
                throw new InvalidArgumentException();
            }
         }

         // Loop through roles and permissions and check each.
         $checkedRoles = array();
         $checkedPermissions = array();
         foreach($roles as $role)
         {
             $checkedRoles[$role] = $this->hasRole($role);
         }
         foreach($permissions as $permission)
         {
             $checkedPermissions[$permission] = $this->can($permission);
         }

         // If validate all and there is a false in either
         // Check that if validate all, then there should not be any false.
         // Check that if not validate all, there must be at least one true.
         if(($options['validate_all'] && !(in_array(false,$checkedRoles) || in_array(false,$checkedPermissions))) ||
            (!$options['validate_all'] && (in_array(true,$checkedRoles) || in_array(true,$checkedPermissions))))
         {
             $validateAll = true;
         }
         else
         {
            $validateAll = false;
         }

         // Return based on option
         if($options['return_type'] == 'boolean')
         {
            return $validateAll;
         }
         elseif($options['return_type'] == 'array')
         {
            return array('roles' => $checkedRoles, 'permissions' => $checkedPermissions);
         } else
         {
            return array($validateAll, array('roles' => $checkedRoles, 'permissions' => $checkedPermissions));
         }
    }

}
