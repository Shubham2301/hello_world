<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkWebForm extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'network_web_form';
    protected $fillable = ['network_id', 'web_form_template_id'];
}
