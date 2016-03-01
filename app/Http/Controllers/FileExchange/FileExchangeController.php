<?php

namespace myocuhub\Http\Controllers\FileExchange;

use Auth;
use Event;
use Storage;

use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Folder;
use myocuhub\Models\FolderHistory;
use myocuhub\Models\File;
use myocuhub\Models\FileHistory;
use myocuhub\User;

class FileExchangeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		$folders = Folder::getActiveFolders($request->id);

		$folderlist = array();

		$i = 0;

		foreach ($folders as $folder) {
			$folderlist[$i]['id'] = $folder->id;
			$folderlist[$i]['parent_id'] = $folder->parent_id;
			$folderlist[$i]['name'] = $folder->name;
			$folderlist[$i]['description'] = $folder->description;
			$folderHistory = $folder->history()->orderBy('created_at', 'desc')->first();
			$folderlist[$i]['modified_by'] = User::find($folderHistory->modified_by)->name;
			$folderlist[$i]['updated_at'] = $folderHistory->updated_at;
			$i++;
		}

		$files = File::getActiveFiles($request->id);

		$filelist = array();

		$i = 0;

		foreach ($files as $file) {
			$filelist[$i]['id'] = $file->id;
			$filelist[$i]['name'] = $file->title;
			$filelist[$i]['description'] = $file->description;
			$fileHistory = $file->history()->orderBy('created_at', 'desc')->first();
			$filelist[$i]['modified_by'] = User::find($fileHistory->modified_by)->name;
			$filelist[$i]['updated_at'] = $fileHistory->updated_at;
			$i++;
		}

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist]);
	}

	public function folderDetails($folder_id=0)
	{
		if(!$folder_id){
			return 'Please provide folder id';	
		}

		$folder = Folder::find($folder_id);

		$folderDetails['name'] = $folder->name;
		$folderDetails['description'] = $folder->description;

		$folderHistory = $folder->history();

		$details = array();
		$i = 0;

		foreach ($folderHistory as $detail) {
			$details[$i]['modified_by'] = $folder->name;
			$details[$i]['description'] = $folder->description;
			$details[$i]['modified_by'] = $detail->modified_by == Auth::user ? 'Me' : $detail->modified_by;
			$details[$i]['updated_at'] = $detail->updated_at;
		}

		return $details;
	}	

	public function createFolder(Request $request)
	{
		$parent_id = $request->parent_id;

		$parent_treepath = '';
		
		$folder = new Folder();
		$folder->name = $request->foldername;
		$folder->description = $request->folderdescription;
		$folder->owner_id = Auth::user()->id;
		$folder->status = '1';

		if($parent_id !="" ){
			$parentFolder = Folder::find($parent_id);
			$parent_treepath = $parentFolder->treepath;
			$folder->parent_id = $parent_id;
		}	

		$folder->save();

		$id = $folder->id;

		$newFolder = Folder::find($id);
		$newFolder->treepath = $parent_treepath . '/' . $id . '/';
		$newFolder->save();

		$folderHistory = new FolderHistory();
		$folderHistory->folder_id = $id;
		$folderHistory->modified_by = Auth::user()->id;
		$folderHistory->save();

		Storage::makeDirectory($request->foldername);

		return redirect('file_exchange');
	}

	public function uploadDocument(Request $request)
	{
		$parent_id = $request->parent_id;

		$parent_treepath = '';
		
		$file = new File();
		// TOOD - Genearte a unique name for the file.
		$file->name = $request->filename;
		$file->title = $request->filename;
		$file->description = $request->filedescription;
		$file->creator_id = Auth::user()->id;
		$file->status = '1';

		if($parent_id !="" ){
			$parentFolder = Folder::find($parent_id);
			$parent_treepath = $parentFolder->treepath;
			$file->parent_id = $parent_id;
		}	

		$file->save();

		$id = $file->id;

		$newFile = File::find($id);
		$treepath = $parent_treepath . '/' . $id . '/';
		$newFile->treepath = $treepath;
		$newFile->save();

		$fileHistory = new FileHistory();
		$fileHistory->file_id = $id;
		$fileHistory->modified_by = Auth::user()->id;
		$fileHistory->save();

		Storage::put(
            Auth::user()->id . '' . $treepath,
            file_get_contents($request->file('add_document'))
        );

		return redirect('file_exchange');
	}
}
