<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderType extends Model
{
    //

    public static function indexed()
    {
        $providerTypes = self::orderBy('name')->get(['id', 'name']);

        foreach ($providerTypes as $type) {
            $indexed[$type->id] = $type->name;
        }

        return $indexed;
    }
}
