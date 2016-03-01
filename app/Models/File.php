<?php

namespace myocuhub\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;

use myocuhub\Models\FileHistory;
use myocuhub\Model\FileShare;

class File extends Model
{
    public function history()
    {
    	return $this->hasMany(FileHistory::class);
    }

    public function sharedwith()
    {
    	return $this->hasMany(FileShare::class);
    }

    public static function getActiveFiles($folder_id=0) {
    	if(!$folder_id){
    		return File::where('status', '=', '1')
    			->where('creator_id', '=', Auth::user()->id)
    			->orderBy('title', 'asc')->get();
    	}

    	return File::where('status', '=', '1')
    		->where('creator_id', '=', Auth::user()->id)
    		->where('folder_id', '=', $folder_id)
    		->orderBy('title', 'asc')->get();
    }
}
