<?php

namespace myocuhub\Http\Controllers\Admin;

use Auth;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\NetworkUser;
use myocuhub\Models\UserLevel;
use myocuhub\Network;
use myocuhub\Role;
use myocuhub\Role_user;
use myocuhub\User;
use myocuhub\Usertype;
use myocuhub\Models\Menu;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$users = User::all();
		$data = array();
		$data['user_active'] = true;
		return view('admin.users.index')->with('users', $users)->with('data', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$user = array();
		$userID = Auth::user()->id;
		$user = User::find($userID)->toArray();
		$user = array_fill_keys(array_keys($user), null);
		$user['role_id'] = '';
		$userTypes = $this->getUserTypes();
		$roles = $this->getRoles();
		$userLevels = $this->getUserLevels();
		$data['url'] = '/administration/users';
		$data['user_active'] = true;
		$user['network_id'] = '';
		$networkData = [];
		$networks = Network::all();
		if (session('user-level') == 1) {
			foreach ($networks as $network) {
				$networkData[$network->id] = $network->name;
			}
		}
        $menu_options = Menu::all();
        $menuData = [];
        foreach ($menu_options as $menu_option) {
            if($menu_option->id != 3 && $menu_option->id != 5)
            $menuData[$menu_option->id] = $menu_option->display_name;
        }

		return view('admin.users.create')->with(['userTypes' => $userTypes, 'roles' => $roles, 'userLevels' => $userLevels])->with('data', $data)->with('user', $user)->with('networks', $networkData)->with('menuoption', $menuData);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {

		if ($request->input('password') == $request->input('password_confirmation')) {
			$user = new User;

			// Save the User information
			$user->title = $request->input('title');
			$user->firstname = $request->input('firstname');
			$user->middlename = $request->input('middlename');
			$user->lastname = $request->input('lastname');

			// TODO
			// Auto generate password
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
			$user->level = $request->input('userlevel');
			$user->menu_id = $request->input('landing_page');

			$user->save();

			$roles = array();
			$roles = $request->input('role');
			foreach ($roles as $role) {
				$save_role = new Role_user();
				$role_id = Role::where('display_name', '=', $role)->first();
				$save_role->user_id = $user->id;
				$save_role->role_id = $role_id->id;
				$save_role->save();
			}

			if ($user) {
				$request->session()->flash('success', 'User Created Successfully!');

				$networkUser = new NetworkUser;
				$networkUser->user_id = $user->id;
				if (session('user-level') == 1) {
					$networkUser->network_id = $request->input('user_network');
				} else {
					$networkUser->network_id = session('network_id');
				}
				$networkUser->save();

				$action = 'new user created';
				$description = '';
				$filename = basename(__FILE__);
				$ip = $request->getClientIp();
				Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
				return redirect('administration/users');
			} else {
				return redirect()->back();
			}
		} else {
			$request->session()->flash('error', 'Passwords do not match');
			return redirect()->back();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$user = User::find($id);
		return view('admin.users.show')->with('user', $user);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id) {
		$level = Auth::user()->level;
		$user = User::find($id);
		if ($level > $user->level) {
			$request->session()->flash('error', 'Not Allowed!');
			return redirect('administration/users');
		} else {
			$data = array();
			$role_ids = Role_user::where('user_id', '=', $id)->get();
			foreach ($role_ids as $role_id) {
				$role = Role::find($role_id->role_id);
				$user[$role->display_name] = $role->display_name;
			}
			$userTypes = $this->getUserTypes();
			$roles = $this->getRoles();
			$userLevels = $this->getUserLevels();
            $menu_options = Menu::all();
            $menuData = [];
            foreach ($menu_options as $menu_option) {
                if($menu_option->id != 3 && $menu_option->id != 5)
                $menuData[$menu_option->id] = $menu_option->display_name;
            }
			$data['user_active'] = true;
			$data['url'] = '/administration/users/update/' . $id;
			return view('admin.users.create')->with('user', $user)->with(['userTypes' => $userTypes, 'roles' => $roles, 'userLevels' => $userLevels, 'menuoption' => $menuData])->with('data', $data);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$user = User::find($id);

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
		$user->level = $request->input('userlevel');
        $user->menu_id = $request->input('landing_page');

		$user->save();

		$previous_roles = Role_user::where('user_id', '=', $user->id)->get();
		$i = 0;
		$previous_roles_id = array();
		foreach ($previous_roles as $previous_role) {
			$previous_roles_id[$i] = $previous_role->role_id;
			$i++;
		}
		$i = 0;
		$new_roles = $request->input('role');
		$new_roles_id = array();
		foreach ($new_roles as $new_role) {
			$new_role_name = Role::where('display_name', '=', $new_role)->first();
			$new_roles_id[$i] = $new_role_name->id;
			$i++;
		}
		$roles_diff = array_intersect($previous_roles_id, $new_roles_id);
		if (isset($roles_diff)) {
			$remove_roles = array_diff($previous_roles_id, $new_roles_id);
			foreach ($remove_roles as $remove_role) {
				$delete_role = Role_user::where('role_id', '=', $remove_role)->delete();
				$key = array_search($remove_role, $remove_roles);
				unset($remove_roles[$key]);
			}
			$add_roles = array_diff($new_roles_id, $previous_roles_id);
			foreach ($add_roles as $add_role) {
				$new_role = new Role_user();
				$new_role->user_id = $user->id;
				$new_role->role_id = $add_role;
				$new_role->save();
			}
		}

		$action = 'update user of id =' . $id;
		$description = '';
		$filename = basename(__FILE__);
		$ip = $request->getClientIp();
		Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

		return redirect('/administration/users');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	public function getUserTypes() {
		$userTypes = Usertype::all();
		$userTypeArray = array();
		foreach ($userTypes as $userType) {
			$userTypeArray[$userType->id] = $userType->name;
		}
		return $userTypeArray;
	}

	public function getRoles() {
		$roles = Role::all();
		$roleArray = array();
		foreach ($roles as $role) {
			$roleArray[$role->name] = $role->display_name;
		}
		return $roleArray;
	}

	public function getUserLevels() {
		$level = Auth::user()->level;
		$userLevels = UserLevel::where('id', '>=', $level)->get();
		$userLevelArray = array();
		foreach ($userLevels as $userLevel) {
			$userLevelArray[$userLevel->id] = $userLevel->name;
		}
		return $userLevelArray;
	}

	public function search(Request $request) {
		$userID = Auth::user()->id;

		$tosearchdata = json_decode($request->input('data'), true);
		if (session('user-level') == 1) {
			$users = User::where('firstname', 'LIKE', '%' . $tosearchdata['value'] . '%')
				->orWhere('middlename', 'LIKE', '%' . $tosearchdata['value'] . '%')
				->orWhere('lastname', 'LIKE', '%' . $tosearchdata['value'] . '%')->paginate(5);
		} else {
			$users = User::getUsersByName($tosearchdata['value'])->paginate(5);
		}

		$data = [];
		$data[0]['total'] = $users->total();
		$data[0]['lastpage'] = $users->lastPage();
		$data[0]['currentPage'] = $users->currentPage();
		$i = 0;
		foreach ($users as $user) {
			$data[$i]['id'] = $user->user_id;
			$data[$i]['name'] = $user->lastname . ', ' . $user->firstname;
			$data[$i]['email'] = $user->email;
            if($user->level)
                $data[$i]['level'] = UserLevel::find($user->level)->name;
            else
                $data[$i]['level'] = 'Undefined';
			$data[$i]['practice'] = 'Ocuhub';
			if ($network = User::getNetwork($user->id)) {
				$data[$i]['practice'] = $network->name;
			}
			if ($user->practice_id) {
				$data[$i]['practice'] = Practice::find($user->practice_id)->name;
			}
			$i++;
		}
		return json_encode($data);
	}

	public function editProfile() {
		$user = Auth::user();

		$profile['id'] = $user->id;
		$profile['name'] = $user->lastname . ', ' . $user->firstname;
		$profile['lastname'] = $user->lastname;
		$profile['firstname'] = $user->firstname;
		$profile['title'] = $user->title;
		$profile['cellphone'] = $user->title;

		return view('layouts.edit-profile')->with('profile', $profile);
	}

	public function updateProfile(Request $request) {
		$user = Auth::user();

		$user->title = $request->title;
		$user->lastname = $request->lastname;
		$user->firstname = $request->firstname;

		$password = $request->password;
		$confirmation = $request->password_confirmation;
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
}
