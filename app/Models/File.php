<?php

namespace myocuhub\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use myocuhub\Models\FileHistory;
use myocuhub\Models\FileShare;
use myocuhub\Models\Folder;

class File extends Model {
	public function history() {
		return $this->hasMany(FileHistory::class);
	}

	public function sharedwith() {
		return $this->hasMany(FileShare::class);
	}

	public static function getFiles($folder_id = 0, $network_id = null, $active = 1) {
		if ($folder_id == null) {
			return File::where('status', '=', $active)
				->where('creator_id', '=', Auth::user()->id)
				->where('network_id', $network_id)
				->whereNull('folder_id')
				->orderBy('title', 'asc')->get();
		}
		$files =  File::where('status', '=', $active)
			->where('folder_id', '=', $folder_id)
			->where('network_id', $network_id)
			->orderBy('title', 'asc')->get();

			$fileObj = new File;
			$i = 0;
			foreach ($files as $file) {
			if(!$fileObj->checkShowStatus($file)){
				$files->forget($i);
			}
			$i++;
			}
			return $files;

	}

	public function sharedWithUser($userId){
		return $this->sharedwith()->where('user_id', '=', $userId)->orderBy('created_at', 'desc')->first();
	}

	public function isEditable(){
		$userId = \Auth::user()->id;
			$directShare =  $this->sharedWithUser($userId);
		if($directShare){
			if($directShare->editable)
				return true;
		}
		if($this->folder_id){
			return Folder::find($this->folder_id)->isEditable();
		}

		return false;
	}

	public function checkShowStatus($file){
			$userID = Auth::user()->id;
			if($file->creator_id == $userID){
				return true;
			}
			$folder  = Folder::find($file->folder_id);
			$folderObj = new Folder;
			return $folderObj->checkShowStatus($folder);
	}
}
