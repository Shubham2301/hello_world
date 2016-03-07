<?php

namespace myocuhub\Http\Controllers\FileExchange;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\File;
use myocuhub\Models\FileHistory;
use myocuhub\Models\FileShare;
use myocuhub\Models\Folder;
use myocuhub\Models\FolderHistory;
use myocuhub\Models\FolderShare;
use myocuhub\Network;
use myocuhub\User;
use Storage;

class FileExchangeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		$userID = Auth::user()->id;
		$network = User::getNetwork($userID);
		$networkID = $network->network_id;

		$folders = Folder::getFolders($request->id);

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

		$files = File::getFiles($request->id);

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

		$networkPractices = Network::find($networkID)->practices;

		$i = 0;
		$practices = [];
		foreach ($networkPractices as $practice) {
			$practices[$i]['id'] = $practice->id;
			$practices[$i]['name'] = $practice->name;
			$i++;
		}

		$breadcrumbs = $this->getBreadcrumbs($request);

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist, 'parent_id' => $request->id, 'practices' => $practices, 'breadcrumbs' => $breadcrumbs]);
	}

	public function folderDetails($folder_id = 0) {
		if (!$folder_id) {
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

	public function createFolder(Request $request) {
		$parent_id = $request->parent_id;

		$parent_treepath = '';

		$folder = new Folder();
		$folder->name = $request->foldername;
		$folder->description = $request->folderdescription;
		$folder->owner_id = Auth::user()->id;
		$folder->status = '1';

		$parent_treepath = '/';

		if ($parent_id != "") {
			$parentFolder = Folder::find($parent_id);
			$parent_treepath = $parentFolder->treepath;
			$folder->parent_id = $parent_id;
		}

		$folder->save();

		$id = $folder->id;

		$newFolder = Folder::find($id);
		$treepath = $parent_treepath . '' . $id . '/';
		$newFolder->treepath = $treepath;
		$newFolder->save();

		$folderHistory = new FolderHistory();
		$folderHistory->folder_id = $id;
		$folderHistory->modified_by = Auth::user()->id;
		$folderHistory->save();

		Storage::makeDirectory($treepath);

		// return redirect('file_exchange');
		return redirect()
			->back()
			->withSuccess("Folder '$request->foldername' created.");

	}

	public function uploadDocument(Request $request) {
		$parent_id = $request->parent_id;

		$parent_treepath = '/';

		$file = new File();

		$file->title = $request->filename;
		$file->description = $request->filedescription;
		$file->creator_id = Auth::user()->id;
		$file->status = '1';
		$extension = $request->file('add_document')->getClientOriginalExtension();
		$file->extension = $extension;
		$file->mimetype = $request->file('add_document')->getClientMimeType();
		$file->filesize = $request->file('add_document')->getClientSize();
		// TOOD - Genearte a unique name for the file.
		$fileName = rand(11111, 99999); //.'.'.$extension;
		$file->name = $fileName;

		if ($parent_id != null) {
			$parentFolder = Folder::find($parent_id);
			$parent_treepath = $parentFolder->treepath;
			$file->folder_id = $parent_id;
		}

		$file->save();

		$id = $file->id;

		$newFile = File::find($id);
		$treepath = $parent_treepath; // . '' . $id . '/';
		$newFile->treepath = $treepath;
		$newFile->save();

		$fileHistory = new FileHistory();
		$fileHistory->file_id = $id;
		$fileHistory->modified_by = Auth::user()->id;
		$fileHistory->save();

		Storage::put(
			$parent_treepath . '/' . $fileName . '.' . $file->extension,
			file_get_contents($request->file('add_document')->getRealPath())
		);

		// return redirect('file_exchange');

		return redirect()
			->back()
			->withSuccess("Document '$request->filename' created.");

	}

	public function downloadFile(Request $request) {
		$id = $request->id;

		if ($id == '') {
			return redirect()
				->back()
				->withErrors("Invalid Request!");
		}

		$file = File::find($id);

		$downloadFile = Storage::get($file->treepath . '' . $file->name . '.' . $file->extension);

		return response($downloadFile, 200)
			->header('Content-Type', $file->mimetype)
			->header("Content-Disposition", "attachment; filename=\"" . $file->title . '.' . $file->extension . "\"");
	}

	public function sharedWithMe(Request $request, $sortOnRecent = '') {
		$userId = Auth::user()->id;
		$network = User::getNetwork($userId);
		$networkID = $network->network_id;

		if ($request->id && Folder::find($request->id)->sharedWithUser($userId)) {
			return $this->index($request);
		}

		$sharedfolders = FolderShare::getSharedFoldersForUser($userId); //, $request->id);

		$folderlist = array();

		$i = 0;

		foreach ($sharedfolders as $sharedfolder) {
			$folder = Folder::find($sharedfolder->folder_id);
			if (!$folder->status) {
				continue;
			}
			$folderlist[$i]['id'] = $folder->id;
			$folderlist[$i]['parent_id'] = $folder->parent_id;
			$folderlist[$i]['name'] = $folder->name;
			$folderlist[$i]['description'] = $folder->description;
			$folderHistory = $folder->history()->orderBy('created_at', 'desc')->first();
			$folderlist[$i]['modified_by'] = User::find($folderHistory->modified_by)->name;
			$folderlist[$i]['updated_at'] = $folderHistory->updated_at;
			$i++;
		}

		$folderlist = array_values(array_sort($folderlist, function ($value) {
			return $value['name'];
		}));

		$sharedfiles = FileShare::getSharedFilesForUser($userId); //, $request->id);

		$filelist = array();

		$i = 0;

		foreach ($sharedfiles as $sharedfile) {
			$file = File::find($sharedfile->file_id);
			if (!$file->status) {
				continue;
			}
			$filelist[$i]['id'] = $file->id;
			$filelist[$i]['name'] = $file->title;
			$filelist[$i]['description'] = $file->description;
			$fileHistory = $file->history()->orderBy('created_at', 'desc')->first();
			$filelist[$i]['modified_by'] = User::find($fileHistory->modified_by)->name;
			$filelist[$i]['updated_at'] = $fileHistory->updated_at;
			$i++;
		}

		if ($sortOnRecent != '') {
			$folderlist = array_values(array_sort($folderlist, function ($value) {
				return $value['updated_at'];
			}));

			$filelist = array_values(array_sort($filelist, function ($value) {
				return $value['updated_at'];
			}));
		}

		$networkPractices = Network::find($networkID)->practices;

		$i = 0;

		foreach ($networkPractices as $practice) {
			$practices[$i]['id'] = $practice->id;
			$practices[$i]['name'] = $practice->name;
			$i++;
		}

		$breadcrumbs = $this->getBreadcrumbs($request);

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist, 'parent_id' => $request->id, 'practices' => $practices, 'breadcrumbs' => $breadcrumbs]);
	}

	public function recentShareChanges(Request $request) {
		return $this->sharedWithMe($request, 'true');
	}

	public function deleteFile(Request $request) {
		if (!$request->id) {
			return redirect()
				->back()
				->withSuccess("Invalid Request!");
		}

		$file = File::find($request->id);

		$file->status = 0;
		$file->save();

		return redirect()
			->back()
			->withSuccess("Successfully Deleted!");
	}

	public function showtrash(Request $request) {
		$folders = Folder::getFolders($request->id, 0);
		$userId = Auth::user()->id;
		$network = User::getNetwork($userId);
		$networkID = $network->network_id;

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

		$files = File::getFiles($request->id, 0);

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

		$networkPractices = Network::find($networkID)->practices;

		$i = 0;

		foreach ($networkPractices as $practice) {
			$practices[$i]['id'] = $practice->id;
			$practices[$i]['name'] = $practice->name;
			$i++;
		}

		$breadcrumbs = $this->getBreadcrumbs($request);

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist, 'parent_id' => $request->id, 'practices' => $practices, 'breadcrumbs' => $breadcrumbs]);
	}
	public function shareFilesFolders(Request $request) {

		$editable = ($request->share_writable === 'on') ? 1 : 0;
		$folders = explode(',', $request->share_folders);
		$files = explode(',', $request->share_files);
		$userId = $request->share_users;

		foreach ($folders as $folder) {
			$folderShare = new FolderShare;

			$folderShare->folder_id = $folder;
			$folderShare->user_id = $userId;
			$folderShare->editable = $editable;

			$folderShare->save();
			echo $folderShare;
		}

		foreach ($files as $file) {
			$fileShare = new FileShare;

			$fileShare->file_id = $file;
			$fileShare->user_id = $userId;
			$fileShare->editable = $editable;

			$fileShare->save();
			echo $fileShare;
		}

		return redirect()
			->back()
			->withSuccess("Successfully Shared!");

	}

	public function getBreadcrumbs(Request $request) {

		if ($request->id === null || $request->id === '') {
			$request->session()->forget('breadcrumb');
		} else {
			$folderName = Folder::find($request->id)->name;
			$current = ['id' => $request->id, 'name' => $folderName];
			$breadcrumbs = $request->session()->get('breadcrumb', []);
			if (in_array($current, $breadcrumbs)) {
				do {
					$top = array_pop($breadcrumbs);
				} while (!($top == $current));
				$request->session()->forget('breadcrumb');
				$request->session()->put('breadcrumb', $breadcrumbs);
			}
			$request->session()->push('breadcrumb', $current);
		}

		$breadcrumbs = $request->session()->get('breadcrumb', []);
		return $breadcrumbs;
	}
}
