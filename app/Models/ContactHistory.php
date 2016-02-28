<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model
{
	protected $table = "contact_history";


	public static function getContactHistory($consoleID){
		return self::where('console_id', $consoleID)
			->leftjoin('actions', 'contact_history.action_id', '=', 'actions.id')
			->leftjoin('careconsole', 'contact_history.console_id', '=', 'careconsole.id')
			->whereNull('archived_date')
			->get(['*', 'contact_history.id']);

	}
}
