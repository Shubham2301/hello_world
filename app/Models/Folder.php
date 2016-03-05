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

    public static function getActiveFolders($parent_id=0) {

    	if($parent_id == null){
    		return Folder::where('status', '=', '1')
    			->where('owner_id', '=', Auth::user()->id)
                ->whereNull('parent_id')
    			->orderBy('name', 'asc')->get();
    	}

    	return Folder::where('status', '=', '1')
    		->where('owner_id', '=', Auth::user()->id)
    		->where('parent_id', '=', $parent_id)
    		->orderBy('name', 'asc')->get();
    }

    public function sharedWithUser($userId)
    {   
        return $this->shares()->where('user_id', '=', $userId)->count();
        // return FolderShare::where('user_id', '=', $userId);        
    }
}
