<?php

namespace myocuhub\Http\Controllers\FileExchange;

use Auth;
use DateTime;
use DB;
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
use myocuhub\Models\NetworkUser;
use myocuhub\Models\Practice;
use Event;
use myocuhub\Events\MakeAuditEntry;

class FileExchangeController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		$action = 'Accessed File Exchange';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

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
			$updateDate = new DateTime($folderHistory->updated_at);
			$folderlist[$i]['updated_at'] = $updateDate->format('j F Y');
			$i++;
		}

		$files = File::getFiles($request->id);

		$filelist = array();

		$j = 0;

		foreach ($files as $file) {
			$filelist[$j]['id'] = $file->id;
			$filelist[$j]['name'] = $file->title;
			$filelist[$j]['description'] = $file->description;
			$fileHistory = $file->history()->orderBy('created_at', 'desc')->first();
			$filelist[$j]['modified_by'] = User::find($fileHistory->modified_by)->name;
			$updateDate = new DateTime($fileHistory->updated_at);
			$filelist[$j]['updated_at'] = $updateDate->format('j F Y');
			$j++;
		}

		if ($i==0 && $j==0)
			$empty = 'true';
		else
			$empty = 'false';


		if (session('user-level') === 1) {
			$networkPractices = Practice::all();
		} else {
			$networkPractices = Network::find(session('network-id'))->practices;
		}

		$i = 0;
		$practices = [];
		foreach ($networkPractices as $practice) {
			$practices[$i]['id'] = $practice->id;
			$practices[$i]['name'] = $practice->name;
			$i++;
		}

		$breadcrumbs = $this->getBreadcrumbs($request);
		$accessLink = '/file_exchange';

        $active_link = array();
        $active_link['my_files'] = true;
        $active_link['title'] = 'My Files';
        $isEditable =  true;

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist, 'parent_id' => $request->id, 'practices' => $practices, 'breadcrumbs' => $breadcrumbs, 'empty' => $empty , 'openView' => 'index', 'accessLink' => $accessLink, 'active_link' => $active_link, 'isEditable'=> $isEditable ]);
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
		$networkId = session('network-id');
		$parent_treepath = "/" . $networkId . '/' . Auth::user()->id . "/";

		$folder = new Folder();
		$folder->name = $request->foldername;
		$folder->description = $request->folderdescription;
		$folder->owner_id = Auth::user()->id;
		$folder->status = '1';

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

		$action = "Folder $request->foldername created.";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return redirect()
			->back()
			->withSuccess("Folder '$request->foldername' created.");

	}

	public function uploadDocument(Request $request) {
		$parent_id = $request->parent_id;

		$networkId = session('network-id');
		$parent_treepath = "/" . $networkId . '/' . Auth::user()->id . "/";

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

		$action = 'Document ' . $request->filename . ' created.';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

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

		$action = 'File Downloaded ' . $file->title;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return response($downloadFile, 200)
			->header('Content-Type', $file->mimetype)
			->header("Content-Disposition", "attachment; filename=\"" . $file->title . '.' . $file->extension . "\"");
	}

	public function sharedWithMe(Request $request, $sortOnRecent = '') {

		$userId = Auth::user()->id;

		$sharedfolders = FolderShare::getSharedFoldersForUser($userId, $request->id);
		$isEditable = false;

    if($request->id != null && $request->id !='' )
    {
			$parentFolder = Folder::find($request->id);
			if($parentFolder){
    		$isEditable = $parentFolder->isEditable();
    	}
    }
		$folderlist = array();

		$i = 0;

		foreach ($sharedfolders as $sharedfolder) {
			$folder = Folder::find($sharedfolder['folder_id']);
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

		$sharedfiles = FileShare::getSharedFilesForUser($userId, $request->id);

		$filelist = array();

		$j = 0;

		foreach ($sharedfiles as $sharedfile) {
			$file = File::find($sharedfile['file_id']);
			if (!$file->status) {
				continue;
			}
			$filelist[$j]['id'] = $file->id;
			$filelist[$j]['name'] = $file->title;
			$filelist[$j]['description'] = $file->description;
			$fileHistory = $file->history()->orderBy('created_at', 'desc')->first();
			$filelist[$j]['modified_by'] = User::find($fileHistory->modified_by)->name;
			$filelist[$j]['updated_at'] = $fileHistory->updated_at;
			$j++;
		}

		if ($i==0 && $j==0)
			$empty = 'true';
		else
			$empty = 'false';

		if ($sortOnRecent != '') {
			$folderlist = array_values(array_sort($folderlist, function ($value) {
				return $value['updated_at'];
			}));

			$filelist = array_values(array_sort($filelist, function ($value) {
				return $value['updated_at'];
			}));
		}

		if (session('user-level') === 1) {
			$networkPractices = Practice::all();
		} else {
			$networkPractices = Network::find(session('network-id'))->practices;
		}

		$i = 0;

		$practices = [];

		foreach ($networkPractices as $practice) {
			$practices[$i]['id'] = $practice->id;
			$practices[$i]['name'] = $practice->name;
			$i++;
		}

		$breadcrumbs = $this->getBreadcrumbs($request);

		$accessLink = '/sharedWithMe';

        $active_link = array();

		if($sortOnRecent == 'true'){
			$action = 'Accessed Recent Shared Changes in File Exchange';
            $active_link['recent_share_changes'] = true;
            $active_link['title'] = 'Recent Share Changes';
		} elseif ($sortOnRecent == '' || $sortOnRecent == 'false') {
			$action = 'Accessed Shared With Me in File Exchange';
            $active_link['shared_with_me'] = true;
            $active_link['title'] = 'Shared With Me';
		}
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist, 'parent_id' => $request->id, 'practices' => $practices, 'breadcrumbs' => $breadcrumbs, 'empty' => $empty , 'openView' => 'sharedWithMe', 'accessLink' => $accessLink, 'active_link' => $active_link, 'isEditable'=> $isEditable]);
	}

	public function recentShareChanges(Request $request) {
		return $this->sharedWithMe($request, 'true');
	}

	public function deleteFile(Request $request) {

		$folders =[];
		$files = [];
		if($request->delete_folders != ''){
			$folders = explode(',', $request->delete_folders);
		}
		if($request->delete_files != ''){
			$files = explode(',', $request->delete_files);
		}
		$foldersAudit = '';
		foreach ($folders as $folderID) {
			$folder = Folder::find($folderID);
			if($folder){
					$folder->status = 0;
					$folder->save();
					$foldersAudit .= $folderID. ', ';
			}
		}
		$filesAudit = '';
		foreach ($files as $fileID) {
			$file = File::find($fileID);
			if($file){
					$file->status = 0;
					$file->save();
					$filesAudit .= $fileID. ', ';
			}
		}

		$action = "Files Delete : $filesAudit ; FolderDeleted : $foldersAudit";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return redirect()
			->back()
			->withSuccess("Successfully Deleted!");
	}

	public function showtrash(Request $request) {

		$folders = Folder::getFolders($request->id, 0);
		$folderlist = array();

		$i = 0;
		foreach ($folders as $folder) {
			if(!$this->checkParentStatus($folder->id))
				continue;
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

		$j = 0;

		foreach ($files as $file) {
			$filelist[$j]['id'] = $file->id;
			$filelist[$j]['name'] = $file->title;
			$filelist[$j]['description'] = $file->description;
			$fileHistory = $file->history()->orderBy('created_at', 'desc')->first();
			$filelist[$j]['modified_by'] = User::find($fileHistory->modified_by)->name;
			$filelist[$j]['updated_at'] = $fileHistory->updated_at;
			$j++;
		}

		if ($i==0 && $j==0)
			$empty = 'true';
		else
			$empty = 'false';

		if (session('user-level') === 1) {
			$networkPractices = Practice::all();
		} else {
			$networkPractices = Network::find(session('network-id'))->practices;
		}

		$i = 0;
		$practices = [];
		foreach ($networkPractices as $practice) {
			$practices[$i]['id'] = $practice->id;
			$practices[$i]['name'] = $practice->name;
			$i++;
		}

		$breadcrumbs = $this->getBreadcrumbs($request);
		$accessLink = '#';

		$action = 'Accessed Trash in File Exchange';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        $active_link = array();
        $active_link['trash'] = true;
        $active_link['title'] = 'Trash';
        $isEditable = false;

		return view('file_exchange.index')->with(['folderlist' => $folderlist, 'filelist' => $filelist, 'parent_id' => $request->id, 'practices' => $practices, 'breadcrumbs' => $breadcrumbs, 'empty' => $empty, 'openView' => 'trash', 'accessLink' => $accessLink, 'active_link' => $active_link, 'isEditable' => $isEditable]);
	}
	public function shareFilesFolders(Request $request) {

		$editable = ($request->share_writable === 'on') ? 1 : 0;

		$toAllUsers = ($request->share_with_network === 'on') ? true : false;

		$folders =[];
		$files = [];
		if($request->share_folders != '')
			$folders = explode(',', $request->share_folders);
		if($request->share_files != '')
			$files = explode(',', $request->share_files);

		if($toAllUsers){
			$this->shareWithNetwork($files , $folders, $editable );
		} else {
			$userId = $request->share_users;
			foreach ($folders as $folder) {
				$data = [];
				$data['folder_id'] = $folder;
				$data['user_id'] = $userId;
				$folderShare = FolderShare::where($data)->first();
				if(!$folderShare){
					$data['editable'] = $editable;
					$folderShare = FolderShare::create($data);
				}
				else{
					$folderShare->editable = $editable;
					$folderShare->save();
				}
			}
			foreach ($files as $file) {
				$data = [];
				$data['file_id'] = $file;
				$data['user_id'] = $userId;
				$fileShare = FileShare::where($data)->first();
				if(!$fileShare){
					$data['editable'] = $editable;
					$fileShare = FileShare::create($data);
				}
				else{
					$fileShare->editable = $editable;
					$fileShare->save();
				}
			}
		}

		$action = "Shared Files : $request->share_files ; Shared Folders : $request->share_folders";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

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

	public function show(Request $request) {
		$type = $request->name;
		$id = $request->id;
		$fromView = $request->fromView;
		if($type == 'folder') {

			$folderInfo = array();
			$folderInfo['can_edit'] = '';
			$folder = Folder::find($id);
			$folderInfo['name'][0] = $folder->name;
			$folderInfo['description'][0] = $folder->description;
			$folderHistory = FolderHistory::where('folder_id', '=', $id)->orderBy('created_at', 'desc')->take(3)->get();
			$i = 0;
			foreach($folderHistory as $history){
				$folderInfo['modified_by'][$i] = User::find($history->modified_by)->name;
				$updateDate = new DateTime($history->updated_at);
				$folderInfo['updated_at'][$i] = $updateDate->format('j F Y');
				$i++;
			}

			if($fromView == 'sharedWithMe' && !$folder->isEditable()) {
				$folderInfo['can_edit'] = 'disabled';
			}

			return($folderInfo);

		}
		else {

			$fileInfo = array();
			$fileInfo['can_edit'] = '';
			$file = File::find($id);
			$fileInfo['name'][0] = $file->title;
			$fileInfo['description'][0] = $file->description;
			$fileHistory = FileHistory::where('file_id', '=', $id)->orderBy('created_at', 'desc')->take(3)->get();
			$i = 0;
			foreach($fileHistory as $history){
				$fileInfo['modified_by'][$i] = User::find($history->modified_by)->name;
				$updateDate = new DateTime($history->updated_at);
				$fileInfo['updated_at'][$i] = $updateDate->format('j F Y');
				$i++;
			}


			if($fromView == 'sharedWithMe' && !$file->isEditable()) {
				$fileInfo['can_edit'] = 'disabled';
			}
			return($fileInfo);
		}
	}


	public function changeDescription(Request $request) {
		$id = $request->id;
		$description = $request->description;
		$type = $request->name;
		$data = array();
		$data['id'] = $id;
		$data['name'] = $type;
		$data['description'] = $description;
		if($type == 'folder') {
			$folder = Folder::find($id);
			$folder->description = $description;
			$folder->save();
			$folderHistory = new FolderHistory();
			$folderHistory->folder_id = $id;
			$folderHistory->modified_by = Auth::user()->id;
			$folderHistory->save();
		}
		else {
			$file = File::find($id);
			$file->description = $description;
			$file->save();
			$fileHistory = new FileHistory();
			$fileHistory->file_id = $id;
			$fileHistory->modified_by = Auth::user()->id;
			$fileHistory->save();
		}

		$action = "Changed Description of $type of id $id";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return $data;
	}

	public function shareWithNetwork($files , $folders, $editable ) {

		$networkUsers = NetworkUser::where('network_id', session('network-id'))->get();

		foreach ($folders as $folder) {
			$data = [];
			$data['folder_id'] = $folder;

			foreach($networkUsers as $user)
			{
				if($user->user_id == Auth::user()->id){
					continue;
				}
				$data['user_id'] = $user->user_id;
				$folderShare = FolderShare::where($data)->first();
				if(!$folderShare){
					$data['editable'] = $editable;
					$folderShare = FolderShare::create($data);
				}
				else{
					$folderShare->editable = $editable;
					$folderShare->save();
				}
			}
		}
		foreach ($files as $file) {
			$data = [];
			$data['file_id'] = $file;
			foreach($networkUsers as $user)
			{
				if($user->user_id == Auth::user()->id){
					continue;
				}
				$data['user_id'] = $user->user_id;
				$fileShare = FileShare::where($data)->first();
				if(!$fileShare){
					$data['editable'] = $editable;
					$fileShare = FileShare::create($data);
				}
				else{
					$fileShare->editable = $editable;
					$fileShare->save();
				}
				
			}
		}
	}

	public function checkParentStatus($folderID){
		$parentID = explode('/', Folder::find($folderID)->treepath);
		array_shift($parentID);
		array_shift($parentID);
		array_shift($parentID);
		array_pop($parentID);
		array_pop($parentID);
		foreach($parentID as $id){
			$status = Folder::find($id)->status;
			if($status == 0)
				return false;
		}
		return true;

	}

	public function restoreFilesFolders(Request $request){
		$folders =[];
		$files = [];
		if($request->restore_folders != '')
			$folders = explode(',', $request->restore_folders);
		if($request->restore_files != '')
			$files = explode(',', $request->restore_files);

		$foldersAudit = '';
		foreach ($folders as $folderID) {
			$folder = Folder::find($folderID);
			if($folder){
				$folder->status = 1;
				$folder->save();
				$foldersAudit .= $folderID. ', ';
			}
		}
		$filesAudit = '';
		foreach ($files as $fileID) {
			$file = File::find($fileID);
			if($file){
				$file->status = 1;
				$file->save();
				$filesAudit .= $fileID. ', ';
			}
		}

		

		$action = "Restored Files : $filesAudit ; Restored Folders : $foldersAudit";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return redirect()
			->back()
			->withSuccess("Successfully Restored!");
	}

	public function changeItemName(Request $request){
		$id = $request->id;
		$itemName = $request->itemName;
		$type = $request->name;
		$data = array();
		$data['id'] = $id;
		$data['name'] = $type;
		$data['itemName'] = $itemName;
		if($type == 'folder') {
			$folder = Folder::find($id);
			$folder->name = $itemName;
			$folder->save();
			$folderHistory = new FolderHistory();
			$folderHistory->folder_id = $id;
			$folderHistory->modified_by = Auth::user()->id;
			$folderHistory->save();
		}
		else {
			$file = File::find($id);
			$file->title = $itemName;
			$file->save();
			$fileHistory = new FileHistory();
			$fileHistory->file_id = $id;
			$fileHistory->modified_by = Auth::user()->id;
			$fileHistory->save();
		}
		
		$action = "Changed name of $type of id $id to $itemName";
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return $data;
	}
}
