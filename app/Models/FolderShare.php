<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

use myocuhub\Models\Folder;
use Auth;
class FolderShare extends Model
{
    protected $table = 'foldershares';
	protected $fillable = ['folder_id','user_id','editable'];
	protected $primaryKey = 'folder_id';


    public function folder()
    {
    	return $this->belongsTo(Folder::class);
    }

	public static function getSharedFoldersForUser($userId, $parentID = null)
	{
		if($parentID == null){
			return FolderShare::where('user_id', '=', $userId)->get();
		}

		if(self::isShared($parentID) || self::isParentShared($parentID))
		{
			$folders =  Folder::where('status', '=', 1)
				->where('parent_id', '=', $parentID)
				->orderBy('name', 'asc')->pluck('id');
			$sharedFolders = [];
			$i =0;

			foreach($folders as $id)
			{
				$sharedFolders[$i]['folder_id'] = $id;
				$i++;
			}
			return $sharedFolders;
		}
	}

	public static function isShared($folderId, $userId = 0)
	{
		$userId = Auth::user()->id;
		return FolderShare::where('user_id', '=', $userId)
			->where('folder_id', '=', $folderId)
			->count();
	}

	public static function isParentShared($folderId, $userId = 0) {
		if(self::isShared($folderId))
		{
			return true;
		}
		$userId = Auth::user()->id;
		$parentID = explode('/', Folder::find($folderId)->treepath);
		array_shift($parentID);
		array_pop($parentID);
		array_pop($parentID);
		foreach($parentID as $id){
			if(self::isShared($id, $userId))
			{
				return true;
			}
		}
		return false;
	}
}
