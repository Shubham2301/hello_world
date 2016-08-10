<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;

class Usertype extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getID($name)
    {
        return self::where('name', $name)
            ->first()
            ->id;
    }

}
