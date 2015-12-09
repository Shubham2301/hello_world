<?php

namespace myocuhub\Http\Controllers\Admin;

use myocuhub\User;
use myocuhub\Usertype;
use myocuhub\Role;
use Illuminate\Http\Request;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userTypes = $this->getUserTypes();
        $roles = $this->getRoles();
        return view('admin.users.create')->with(['userTypes' => $userTypes, 'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;

        // Save the User information
        $user->title = $request->input('title');
        $user->firstname = $request->input('firstname');
        $user->middlename = $request->input('middlename');
        $user->lastname = $request->input('lastname');
        $user->password = bcrypt($request->input('password'));
        $user->email = $request->input('email');
        $user->npi = $request->input('npi');
        $user->cellphone = $request->input('cellphone');
        $user->sesemail = $request->input('sesemail');
        $user->calendar = $request->input('calendar');
        $user->address1 = $request->input('address1');
        $user->address2 = $request->input('address2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zip = $request->input('zip');
        $user->name = $request->input('firstname') . ' ' . $request->input('middlename') . ' ' . $request->input('lastname');
        $user->usertype_id = $request->input('usertype');

        $user->save();

        $user->assign($request->input('role'));

        if($user) {
            $request->session()->flash('success', 'User Created Successfully!');
            return redirect('users');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.create')->with('user', $user);
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

    public function getUserTypes()
    {
        $userTypes = Usertype::all();
        $userTypeArray = array();
        foreach ($userTypes as $userType) {
            $userTypeArray[$userType->id] = $userType->name;
        }
        return $userTypeArray;
    }

    public function getRoles()
    {
        $roles = Role::all();
        $roleArray = array();
        foreach ($roles as $role) {
            $roleArray[$role->name] = $role->display_name;
        }
        return $roleArray;
    }

}
