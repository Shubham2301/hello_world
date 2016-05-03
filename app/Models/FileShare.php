<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

use myocuhub\Models\File;
use myocuhub\Models\FolderShare;

class FileShare extends Model
{
	protected $table = 'fileshares';
	protected $fillable = ['file_id','user_id','editable'];
	protected $primaryKey = 'file_id';

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public static function getSharedFilesForUser($userId, $folderID = null)
	{
		if($folderID == null )
		{
			return FileShare::where('user_id', '=', $userId)->get();
		}

		if(FolderShare::isParentShared($folderID)){
			$files = File::where('folder_id', '=', $folderID)
				->pluck('id');

			$sharedFiles = [];
			$i =0;
			foreach($files as $id)
			{
				$sharedFiles[$i]['file_id'] = $id;
			}
			return $sharedFiles;
		}
	}

	public static function isShared($fileId, $userId)
	{
		return FileShare::where('user_id', '=', $userId)
			->where('file_id', '=', $fileId)
			->count();
	}
}
