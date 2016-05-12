<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Menu;
use myocuhub\Patient;
use myocuhub\Role_user;
use myocuhub\User;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userID = Auth::user()->id;
        $user = User::find($userID);
        if (isset($user->menu_id)) {
            if ($user->menu_id == 1) {
                return redirect('/directmail');
            } elseif ($user->menu_id == 2) {
                return redirect('/file_exchange');
            } elseif ($user->menu_id == 4) {
                return redirect('/referraltype');
            } elseif ($user->menu_id == 6) {
                return redirect('/careconsole');
            } elseif ($user->menu_id == 7) {
                return redirect('/administration');
            }
        }
        $roles = Role_user::where('user_id', '=', $userID)->get();
        foreach ($roles as $role) {
            if ($role->role_id == 12) {
                return redirect('/careconsole');
                break;
            }
        }
        return redirect('/referraltype');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function editProfile()
    {
        $user = Auth::user();

        $profile['id'] = $user->id;
        $profile['name'] = $user->lastname . ', ' . $user->firstname;
        $profile['lastname'] = $user->lastname;
        $profile['firstname'] = $user->firstname;
        $profile['title'] = $user->title;
        $profile['cellphone'] = $user->title;

        return view('layouts.edit-profile')->with('profile', $profile);
    }

    public function updateProfile(Request $request)
    {
        if ($request->ajax()) {
            if ($request->hasFile('profile_img')) {
                $file = $request->file('profile_img');
                $destinationPath = 'images/temp';
                $extension = $file->getClientOriginalExtension();
                $pictureName = str_random(9) . ".jpg";
                $upload_success = $file->move(public_path() . '/' . $destinationPath, $pictureName);
                return \URL::asset('/images/temp/' . $pictureName);
            }
        }

        $user = Auth::user();
        $user->title = $request->title;
        $user->lastname = $request->lastname;
        $user->firstname = $request->firstname;
        $password = $request->password;
        $confirmation = $request->password_confirmation;

        if ($request->hasFile('profile_img')) {
            $file = $request->file('profile_img');
            $destinationPath = 'images/users/';
            $extension = $file->getClientOriginalExtension();
            $pictureName = 'user_' . Auth::user()->id . '.jpg';
            $upload_success = $file->move(public_path() . '/' . $destinationPath, $pictureName);
        }
        if ($password !== '' && $confirmation !== '') {
            if ($password != $confirmation) {
                $request->session()->flash('error', 'Passwords do not match');
                return redirect()->back();
            }
            $user->password = bcrypt($password);
        }
        $user->save();
        $request->session()->flash('success', 'User Information Updated');
        return redirect()->back();
    }

    public function administration(Request $request)
    {
        $redirectURL = '/referraltype';

        if (Auth::user()->hasRole('user-admin')) {
            $redirectURL = '/administration/users';
            return redirect($redirectURL);
        }
        if (Auth::user()->hasRole('practice-admin')) {
            $redirectURL = '/administration/practices';
            return redirect($redirectURL);
        }
        if (Auth::user()->hasRole('patient-admin')) {
            $redirectURL = '/administration/patients';
            return redirect($redirectURL);
        }
        if (Auth::user()->level == 1) {
            $redirectURL = '/administration/patients';
            return redirect($redirectURL);
        }

        $request->session()->flash('failure', 'You do not have any administrative roles assigned. Please contact your admin for support');
        return redirect($redirectURL);
    }
}
