<?php

namespace myocuhub\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;

use myocuhub\Models\FolderHistory;
use myocuhub\Models\FolderShare;

class Folder extends Model
{

	public function history()
	{
		return $this->hasMany(FolderHistory::class);
	}

	public function shares()
	{
		return $this->hasMany(FolderShare::class);
	}

	public static function getFolders($parent_id=0, $active=1) {

		if($active == 0){
			return Folder::where('status', '=', $active)
				->where('owner_id', '=', Auth::user()->id)
				->orderBy('name', 'asc')->get();
		}


		if($parent_id == null){
			return Folder::where('status', '=', $active)
				->where('owner_id', '=', Auth::user()->id)
				->whereNull('parent_id')
				->orderBy('name', 'asc')->get();
		}

		return Folder::where('status', '=', $active)
			->where('owner_id', '=', Auth::user()->id)
			->where('parent_id', '=', $parent_id)
			->orderBy('name', 'asc')->get();
	}

	public function sharedWithUser($userId)
	{
		return $this->shares()->where('user_id', '=', $userId)->orderBy('created_at', 'desc')->first();
		// return FolderShare::where('user_id', '=', $userId);
	}

	public function isEditable(){
		$userId = \Auth::user()->id;
		$directShare =  $this->sharedWithUser($userId);

		if($directShare){
			if($directShare->editable)
				return true;
		}
		$parentIDs = $this->getParents();
		foreach($parentIDs as $id){
			$data['user_id'] = $userId;
			$data['folder_id'] = $id;
			$shareDetails = FolderShare::where($data)->orderBy('created_at', 'desc')->first();
			if($shareDetails)
			{
				if($shareDetails->editable)
					return true;
			}
		}
		return false;
	}

	public function getParents(){
		$parentIDs = explode('/', $this->treepath);
		array_shift($parentIDs);
		array_pop($parentIDs);
		array_pop($parentIDs);

		return $parentIDs;

	}

}
