<?php namespace Immap\Watchkeeper;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Hashing;
use Illuminate\Database\Eloquent\Model as Eloquent;
class User extends Eloquent implements UserInterface, RemindableInterface {

    protected $fillable = array('username','email','firstname','middlename','lastname');
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function countries()
    {
        return $this->belongsToMany('\Immap\Watchkeeper\Country', 'assigned_countries')->withTimestamps();
    }

    public function usergroup()
    {
        return $this->belongTo('\Immap\Watchkeeper\Usergroup');
    }

   /**
    * Many-to-Many relations with Role
    */
    public function roles()
    {
        return $this->belongsToMany('\Immap\Watchkeeper\Role', 'assigned_roles')->withTimestamps();
    }

    /**
     * Save Countries inputted
     * @param $inputCountries
     */
    public function saveCountries($inputCountries)
    {
        if(! empty($inputCountries))
        {
            $this->countries()->sync($inputCountries);
        }
        else
        {
            $this->countries()->detach();
        }
    }

    public function getFullName()
    {
        return $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
    }
}
