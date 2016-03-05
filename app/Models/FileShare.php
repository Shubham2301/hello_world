<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

use myocuhub\Models\File;

class FileShare extends Model
{
    protected $table = 'fileshares';

    public function file()
    {
    	return $this->belongsTo(File::class);
    }

    public static function getSharedFilesForUser($userId)
    {
    	return FileShare::where('user_id', '=', $userId)->get();
    }

    public static function isShared($fileId, $userId)
    {
    	return FileShare::where('user_id', '=', $userId)
    			->where('file_id', '=', $fileId)
    			->count();
    }
}
