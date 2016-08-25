<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{

    public function getName()
    {
        return $this ? $this->name : '-';
    }

}
