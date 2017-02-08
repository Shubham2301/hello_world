<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    protected $table = "import_history";

    public function network()
    {
        return $this->belongsTo('myocuhub\Network');
    }
}
