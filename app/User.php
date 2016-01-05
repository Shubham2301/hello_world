<?php

namespace myocuhub;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'sesemail', 'password', 'firstname', 'lastname', 'npi'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        if(is_string($role)) 
        {
            return $this->roles->contains('name', $role);
        }  

        return !! $role->intersect($this->roles)->count();

    //     foreach ($role as $r) {
    //         if($this->hasRole($r->name))
    //         {
    //             return true;
    //         }
    //     }
    //     return false;

    }

    public function assign($role)
    {
        if(is_string($role))
        {
            return $this->roles()->save(
                    Role::whereName($role)->firstOrFail()
            );
        }

        return $this->roles()->save($role);
    }

    public function usertype()
    {
        return $this->belongsTo(Usertype::class);
    }

    public static function practiceUser($filters)

    {
        return self::query()
            ->leftjoin('organization_user','users.id','=','organization_user.user_id')

            ->leftjoin('organizations','organization_user.organization_id','=','organizations.id')
            ->leftjoin('practices','organizations.practice_id','=','practices.id')
            ->leftjoin('practice_location','practices.id','=','practice_location.practice_id')
            ->where( function($query) use($filters){
                foreach($filters as $filter)  {
                  $query->where(function($query) use ($filter){
                   switch($filter['type']){
                        case 'pratice_name' :
                           $query->where('practices.name', $filter['value']);
                           break;
                       case 'location' :
                           $query->where('locationname','LIKE','%'.$filter['value'].'%')
                               ->orWhere('city','LIKE','%'.$filter['value'].'%')
                               ->orWhere('state','LIKE','%'.$filter['value'].'%')
                               ->orWhere('addressline1','LIKE','%'.$filter['value'].'%')
                               ->orWhere('addressline2','LIKE','%'.$filter['value'].'%')
                               ->orWhere('country','LIKE','%'.$filter['value'].'%');
                           break;
                       case 'zip' :
                           $query->where('zip',$filter['value']);
                           break;
                       case 'doctor_name' :
                           $query->where('firstname','LIKE','%'.$filter['value'].'%')
                               ->orWhere('middlename','LIKE','%'.$filter['value'].'%')
                               ->orWhere('lastname','LIKE','%'.$filter['value'].'%');
                           break;
                    }

                  });
                }
            })->get();

    }


}
