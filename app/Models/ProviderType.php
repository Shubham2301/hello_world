<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderType extends Model
{

    public static function indexed()
    {
        $providerTypes = self::orderBy('name')->get(['id', 'name']);
        foreach ($providerTypes as $type) {
            $indexed[$type->id] = $type->name;
        }
        return $indexed;
    }

    public static function notSet()
    {
        return 'Unlisted';
    }

    public static function getName($id)
    {
        return ($type = self::find($id)) ? $type->name : self::notSet();
    }

}
