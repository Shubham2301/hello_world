<?php

namespace myocuhub\Http\Controllers\Admin;

use myocuhub\User;
use myocuhub\Usertype;
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
        return view('admin.user')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
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

        $user->save();

        $user->assign($request->input('role'));

        //$user->usertype()->associate($request->input('usertype'));
        //$user->assignUserType($request->input('usertype'));
        //$userType = Usertype::findorFail($request->input('usertype'));
        // var_dump($userType);
        //$userType->users()->save($user);

        //Session::flash('create_user_status', 'User successfully added!');
        return redirect()->back();
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

}
