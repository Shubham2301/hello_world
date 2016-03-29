<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

use myocuhub\Models\Folder;

class FolderShare extends Model
{
    protected $table = 'foldershares';
	protected $fillable = ['folder_id','user_id','editable'];
	protected $primaryKey = 'folder_id';


    public function folder()
    {
    	return $this->belongsTo(Folder::class);
    }

    public static function getSharedFoldersForUser($userId)
    {
    	return FolderShare::where('user_id', '=', $userId)->get();
    }

    public static function isShared($folderId, $userId)
    {
    	return FileShare::where('user_id', '=', $userId)
    			->where('folder_id', '=', $folderId)
    			->count();
    }
}
