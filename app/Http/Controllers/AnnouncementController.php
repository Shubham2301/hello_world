<?php

namespace myocuhub\Http\Controllers;

use Auth;
use DateTime;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Http\Requests;
use myocuhub\Models\Announcement;
use myocuhub\Models\AnnouncementUser;
use myocuhub\Network;
use myocuhub\Role;
use myocuhub\Role_user;
use myocuhub\User;
class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = date("Y-m-d H:i:s");
        $userID = Auth::user()->id;
        $announcements = AnnouncementUser::where('user_id', '=', $userID)->where('archive', '=', '0')->orderBy('announcement_id', 'desc')->get();
        $data = [];
        $i = 0;
        foreach($announcements as $announcement){
            $announcementData = Announcement::find($announcement->announcement_id);
            if(($announcementData->scheduled_date) <= $date){
                $data[$i]['read'] = $announcement->read;
                $data[$i]['archive'] = $announcement->archive;
                $data[$i]['id'] = $announcementData->id;
                $data[$i]['title'] = $announcementData->title;
                $data[$i]['type'] = $announcementData->type;
                $data[$i]['schedule'] = date('m-d-Y', strtotime($announcementData->scheduled_date));
                $data[$i]['priority'] = $announcementData->priority;
                $data[$i]['message'] = $announcementData->message;
                $data[$i]['excerpt'] = substr($announcementData->message, 0, 30);
                $user = User::find($announcementData->created_by_user);
                $data[$i]['from'] = $user->name;
                $i++;
            }
        }
        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $roles = Role::all();
		$roleArray = array();
        $i = 0;
		foreach ($roles as $role) {
			$roleArray[$i][0] = $role->display_name;
			$roleArray[$i][1] = $role->id;
            $i++;
		}
        $networks = array();
        $i = 0;
        if ($user->isSuperAdmin()) {
            $networkList = Network::all();
            foreach ($networkList as $network) {
                $networks[$i][0] = $network->name;
                $networks[$i][1] = $network->id;
                $i++;
            }
        } else {
            $networkList = $user->userNetwork;
            foreach ($networkList as $network) {
                $networks[$i][0] = $network->network->name;
                $networks[$i][1] = $network->network->id;
                $i++;
            }
        }
        $data = [];
        $data['role_data'] = $roleArray;
        $data['user'] = $user->name;
        $data['network_data'] = $networks;
        return json_encode($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $announcement = new Announcement;
        $announcement->title = $request->input('title');
        $announcement->message = $request->input('message');
        $announcement->priority = $request->input('priority');
        $announcement->type = $request->input('type');
        $schedule = new DateTime($request->input('schedule'));
        $announcement->scheduled_date = $schedule->format('Y-m-d 00:00:00');
        $announcement->role_id = $request->input('send_to');
        $announcement->created_by_user = Auth::user()->id;
        $announcement->save();
        $users = Role_user::where('role_id', '=', $request->input('send_to'))->get();
        foreach($users as $user){
            if($userId != $user->user_id && $user->user->userNetwork) {
                foreach ($user->user->userNetwork as $network) {
                    if ($network->network_id == $request->input('network_id')) {
                        $announcementuser = new AnnouncementUser;
                        $announcementuser->user_id = $user->user_id;
                        $announcementuser->announcement_id = $announcement->id;
                        $announcementuser->read = 0;
                        $announcementuser->archive = 0;
                        $announcementuser->save();
                    }
                }   
            }
        }
        return json_encode($announcement->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $userID = Auth::user()->id;
        $announcementData = Announcement::find($request->input('id'));
        $data = [];
        $data['title'] = $announcementData->title;
        $data['message'] = $announcementData->message;
        $data['id'] = $announcementData->id;
        $data['schedule'] = date('m-d-Y', strtotime($announcementData->scheduled_date));
        $user = User::find($announcementData->created_by_user);
        $data['from'] = $user->name;
        $role = Role::find($announcementData->role_id);
        $data['to'] = $role->display_name;
        $announcement = AnnouncementUser::where('user_id', '=', $userID)->where('announcement_id', '=', $announcementData->id)->first();
        if(isset($announcement)){
        $announcementRead = AnnouncementUser::find($announcement->id);
        $announcementRead->read = 1;
        $announcementRead->save();
        }
        return json_encode($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userID = Auth::user()->id;
        $i = 0;

        while(true){
            if($request->input($i)){
                $announcementId = $request->input($i);
                $i++;
                $announcement = AnnouncementUser::where('user_id', '=', $userID)->where('announcement_id', '=', $announcementId)->first();
                if($announcement == null){
                    continue;
                }
                $announcement->read = 1;
                $announcement->save();
            }
            else {
                break;
            }
        }
        return json_encode("true");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $userID = Auth::user()->id;
        $i = 0;
        while(1){
            if($request->input($i)){
                $announcementId = $request->input($i);
                $announcement = AnnouncementUser::where('user_id', '=', $userID)->where('announcement_id', '=', $announcementId)->first();
                $announcementArchive = AnnouncementUser::find($announcement->id);
                $announcementArchive->archive = 1;
                $announcementArchive->save();
                $i++;}
            else
                break;
        }
        return(json_encode('1'));
    }
    /**
     * Return announcements made by the user
     *
     */

    public function get_announcement_by_user(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $announcements = Announcement::where('created_by_user', '=', $id)->orderBy('id', 'desc')->get();
        $data = [];
        $i = 0;
        foreach($announcements as $announcement) {
                $data[$i]['id'] = $announcement->id;
                $data[$i]['title'] = $announcement->title;
                $data[$i]['type'] = $announcement->type;
                $data[$i]['schedule'] = date('m-d-Y', strtotime($announcement->scheduled_date));
                $data[$i]['priority'] = $announcement->priority;
                $data[$i]['message'] = $announcement->message;
                $data[$i]['excerpt'] = substr($announcement->message, 0, 30);
                $role = Role::find($announcement->role_id);
                $data[$i]['from'] = $role->display_name;
                $i++;
        }
        return json_encode($data);
    }
}
