<?php

namespace myocuhub\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use myocuhub\Models\FileHistory;
use myocuhub\Models\FileShare;

class File extends Model {
	public function history() {
		return $this->hasMany(FileHistory::class);
	}

	public function sharedwith() {
		return $this->hasMany(FileShare::class);
	}

	public static function getFiles($folder_id = 0, $active = 1) {

		if ($folder_id == null) {
			return File::where('status', '=', $active)
				->where('creator_id', '=', Auth::user()->id)
				->whereNull('folder_id')
				->orderBy('title', 'asc')->get();
		}

		return File::where('status', '=', $active)
			->where('creator_id', '=', Auth::user()->id)
			->where('folder_id', '=', $folder_id)
			->orderBy('title', 'asc')->get();
	}
}
