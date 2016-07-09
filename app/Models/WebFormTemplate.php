<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class WebFormTemplate extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'web_form_templates';

	public static function get($name = 'pre-op-form') {
		$template = self::where('name', $name)->first(['structure']);
		$template = json_decode($template['structure'], true);
    	return $template;
	}
}
