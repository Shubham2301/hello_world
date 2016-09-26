<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class WebFormNotification extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'web_form_notifications';

	public function network()
    {
        return $this->belongsTo('myocuhub\Network');
    }

    public function user()
    {
        return $this->belongsTo('myocuhub\User');
    }
}
