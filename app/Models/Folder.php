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

    public static function getFolders($parent_id = 0, $network_id = null, $active = 1)
    {
        if ($active == 0) {
            return Folder::where('status', '=', $active)
                ->where('owner_id', '=', Auth::user()->id)
                ->where('network_id', $network_id)
                ->orderBy('name', 'asc')->get();
        }

        if ($parent_id == null) {
            return Folder::where('status', '=', $active)
                ->where('owner_id', '=', Auth::user()->id)
                ->where('network_id', $network_id)
                ->whereNull('parent_id')
                ->orderBy('name', 'asc')->get();
        }

        $folders =  Folder::where('status', '=', $active)
            ->where('parent_id', '=', $parent_id)
            ->where('network_id', $network_id)
            ->orderBy('name', 'asc')->get();
        $i = 0;
        $folderObj = new Folder;
        foreach ($folders as $folder) {
            if (!$folderObj->checkShowStatus($folder)) {
                $folders->forget($i);
            }
            $i++;
        }
        return $folders;

    }

    public function sharedWithUser($userId)
    {
        return $this->shares()->where('user_id', '=', $userId)->orderBy('created_at', 'desc')->first();
        // return FolderShare::where('user_id', '=', $userId);
    }

    public function isEditable()
    {
        $userId = \Auth::user()->id;
        $directShare =  $this->sharedWithUser($userId);

        if ($directShare) {
            if ($directShare->editable) {
                return true;
            }
        }
        $parentIDs = $this->getParents();
        foreach ($parentIDs as $id) {
            $data['user_id'] = $userId;
            $data['folder_id'] = $id;
            $shareDetails = FolderShare::where($data)->orderBy('created_at', 'desc')->first();
            if ($shareDetails) {
                if ($shareDetails->editable) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getParents()
    {
        $parentIDs = explode('/', $this->treepath);
        array_shift($parentIDs);
        array_shift($parentIDs);
        array_shift($parentIDs);
        array_pop($parentIDs);
        array_pop($parentIDs);

        return $parentIDs;
    }

    public function checkShowStatus($folder)
    {
        $userId = Auth::user()->id;
        $data = [];
        if($folder->owner_id == $userId)
        {
         return true;
        }
        $parents = self::find($folder->id)->getParents();
        foreach ($parents as $id) {
            $data['owner_id'] = $userId;
            $data['id'] = $id;
            $showDetails = Folder::where($data)->orderBy('created_at', 'desc')->first();
            if ($showDetails) {
                return true;
            }
        }
        return false;
    }
}
