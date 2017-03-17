<?php

namespace myocuhub\Http\Controllers\Admin;

use Auth;
use Event;
use Illuminate\Http\Request;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Menu;
use myocuhub\Models\NetworkUser;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\ProviderType;
use myocuhub\Models\UserLevel;
use myocuhub\Network;
use myocuhub\Role;
use myocuhub\Role_user;
use myocuhub\User;
use myocuhub\Usertype;
use Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }

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
    public function create()
    {
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
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
        $user_network['network_id'] = [];
        $user['practice_id'] = '';
        $networkData = [];
        if (session('user-level') == 1) {
            $networks = Network::all()->sortBy("name");
            foreach ($networks as $network) {
                $networkData[$network->id] = $network->name;
            }
        } elseif (session('user-level') == 2) {
            $networks = Network::where('id', session('network-id'))->get();
            foreach ($networks as $network) {
                $networkData[$network->id] = $network->name;
                $user_network['network_id'][] = $network->id;
            }
        } else {
            $networks = Auth::user()->userNetwork;
            foreach ($networks as $network) {
                $networkData[$network->network_id] = $network->network->name;
                $user_network['network_id'][] = $network->network_id;
            }
        }

        $i = 0;
        $practices = [];
        $networkPractices = [];
        if (session('user-level') == 2) {
            $networkPractices = Network::find(session('network-id'))->practices;
            foreach ($networkPractices as $practice) {
                if ($practice->manually_created == null) {
                    $practices[$practice->id] = $practice->name;
                }
            }
        } elseif (session('user-level') > 2) {
            $user['practice_id'] = Auth::user()->userPractice->practice_id;
            $practices[Auth::user()->userPractice->practice_id] = Auth::user()->userPractice->practice->name;
        }

        if (session('user-level') > 2) {
            $user['practice_id'] = Auth::user()->userPractice->practice_id;
        }
        $user['password_required'] = 'required';

        $providerTypes = ProviderType::indexed();

        $menu_options = Menu::find([1, 2, 3, 4, 5]);
        $menuData = [];
        foreach ($menu_options as $menu_option) {
            if ($menu_option->landing_page != 0) {
                $menuData[$menu_option->id] = $menu_option->display_name;
            }
        }

        return view('admin.users.create')->with([
            'userTypes' => $userTypes,
            'roles' => $roles,
            'userLevels' => $userLevels,
            'providerTypes' => $providerTypes,
            'user' => $user,
            'user_network' => $user_network,
            'networks' => $networkData,
            'menuoption' => $menuData,
            'practices' => $practices,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        $validator = $this->validateData($request->all());
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $user = new User;


        $user->title = $request->input('title');
        $user->firstname = $request->input('firstname');
        $user->middlename = $request->input('middlename');
        $user->lastname = $request->input('lastname');
        $user->password = bcrypt(str_random(12));
        if ($request->input('password') != '') {
            $user->password = bcrypt($request->input('password'));
        }
        $user->email = $request->input('email');
        $user->npi = $request->input('npi');
        $user->cellphone = $request->input('cellphone');
        $user->sesemail = $request->input('sesemail');
        $user->ses_username = $request->input('ses_username', null);
        $user->calendar = $request->input('calendar');
        $user->address1 = $request->input('address1');
        $user->address2 = $request->input('address2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zip = $request->input('zip');
        $user->name = $request->input('firstname') . ' ' . $request->input('middlename') . ' ' . $request->input('lastname');
        $user->usertype_id = $request->input('usertype');
        $user->level = $request->input('userlevel');
        $user->acc_key = $request->input('acc_key');
        $user->speciality = $request->input('speciality');
        $user->provider_type_id = $request->input('provider_type_id') ?: null;

        $user->two_factor_auth = $request->input('two_factor_auth') ? $request->input('two_factor_auth') : 0;
        $roles = array();
        $roles = $request->input('role', []);

        if ($request->input('landing_page') != '') {
            $user->menu_id = $request->input('landing_page');
        } elseif (in_array("Care Coordinator", $roles)) {
            $user->menu_id = 6;
        } else {
            $user->menu_id = 4;
        }

        if (!$user->save()) {
            return redirect()->back();
        }

        $roles = array();
        $roles = $request->input('role', []);
        foreach ($roles as $role) {
            $save_role = new Role_user();
            $role_id = Role::where('display_name', '=', $role)->first();
            $save_role->user_id = $user->id;
            $save_role->role_id = $role_id->id;
            $save_role->save();
        }
        $request->session()->flash('success', 'User Created Successfully!');

        if (session('user-level') == 1) {
            if ($user->level == 2) {
                $networkUser = new NetworkUser;
                $networkUser->user_id = $user->id;
                $networks = $request->input('network');
                $networkUser->network_id = $networks[0];
                $networkUser->save();
            } elseif ($user->level > 2) {
                $practiceUser = new PracticeUser;
                $practiceUser->user_id = $user->id;
                $practiceUser->practice_id = $request->input('user_practice');
                $practiceUser->save();

                foreach ($request->input('network') as $value) {
                    $networkUser = new NetworkUser;
                    $networkUser->user_id = $user->id;
                    $networkUser->network_id = $value;
                    $networkUser->save();
                }
            }
        } elseif (session('user-level') == 2) {
            if ($user->level > 2) {
                $practiceUser = new PracticeUser;
                $practiceUser->user_id = $user->id;
                $practiceUser->practice_id = $request->input('user_practice');
                $practiceUser->save();
            }
            $networkUser = new NetworkUser;
            $networkUser->user_id = $user->id;
            $networkUser->network_id = session('network-id');
            $networkUser->save();
        } else {
            $practiceUser = new PracticeUser;
            $practiceUser->user_id = $user->id;
            $practiceUser->practice_id = Auth::user()->userPractice->practice_id;
            $practiceUser->save();

            $userNetworks = NetworkUser::where('user_id', Auth::user()->id)->get();
            foreach ($userNetworks as $network) {
                $networkUser = new NetworkUser;
                $networkUser->user_id = $user->id;
                $networkUser->network_id = $network->network_id;
                $networkUser->save();
            }
        }

        $action = 'New user created';
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

        return redirect('administration/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userID)
    {
        $user = User::find($userID);

        $data['user'] = $user;
        $data['usertype'] = '';
        if ($user->usertype) {
            $data['usertype'] = $user->usertype->name;
        }

        if ($user->providerType) {
            $data['provider_type'] = $user->providerType->name;
        } else {
            $data['provider_type'] = ProviderType::notSet();
        }

        $data['network'] = ' ';
        if ($user->userNetwork->first()) {
            $data['network'] = $user->userNetwork->first()->network->name;
        }

        $data['Practice'] = '';
        if (User::getPractice($userID)) {
            $data['Practice'] = User::getPractice($userID);
        }

        $data['Roles'] = $user->roles;

        return view('admin.users.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
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
            $userLevels = $this->getUserLevels($user->level);
            $networkData = [];
            $user_network['network_id'] = [];

            if ($user['level'] == 2) {
                $userNetwork = $user->userNetwork;
                foreach ($userNetwork as $network) {
                    $networkData[$network->network_id] = $network->network->name;
                }
            } elseif ($user['level'] == 3 && session('user-level') == 1) {
                $practiceNetworks = $user->userPractice->practice->practiceNetwork;
                foreach ($practiceNetworks as $practiceNetwork) {
                    $networkData[$practiceNetwork->network->id] = $practiceNetwork->network->name;
                }
            } elseif ($user['level'] == 3 && session('user-level') != 1) {
                $user_networks = NetworkUser::where('user_id', '=', $id)->get();
                foreach ($user_networks as $network) {
                    $networkData[$network->network_id]  = $network->network->name;
                }
            }

            if ($user['level'] != 1) {
                $user_networks = NetworkUser::where('user_id', '=', $id)->get();
                foreach ($user_networks as $network) {
                    $user_network['network_id'][]  = $network->network_id;
                }
            }

            $networkPractices = [];
            if ($user->userPractice) {
                $networkPractices = Practice::where('id', $user->userPractice->practice_id)->withTrashed()->get();
            }

            $practices = [];
            foreach ($networkPractices as $practice) {
                $practices[$practice->id] = $practice->name;
            }

            $menu_options = Menu::find([1, 2, 3, 4, 5]);

            if (isset($user['Care Coordinator'])) {
                $menu_options = Menu::find([1, 2, 3, 4, 5, 6]);
            }

            $menuData = [];
            foreach ($menu_options as $menu_option) {
                if ($menu_option->landing_page != 0) {
                    $menuData[$menu_option->id] = $menu_option->display_name;
                }
            }

            $user['practice_id'] = ($practice = User::getPractice($id)) ? $practice->id : '';
            $data['user_active'] = true;
            $data['url'] = '/administration/users/update/' . $id;

            $user['password_required'] = '';

            $providerTypes = ProviderType::indexed();

            return view('admin.users.create')
                ->with([
                    'userTypes' => $userTypes,
                    'providerTypes' => $providerTypes,
                    'roles' => $roles,
                    'userLevels' => $userLevels,
                    'menuoption' => $menuData,
                    'data' => $data,
                    'networks' => $networkData,
                    'practices' => $practices,
                    'user' => $user,
                    'user_network' => $user_network
                ]);
        }
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
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        $validator = $this->validateData($request->all(), $id);
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->errors()->first());
            return redirect()->back();
        }

        $user = User::find($id);
        $user->title = $request->input('title');
        $user->firstname = $request->input('firstname');
        $user->middlename = $request->input('middlename');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->npi = $request->input('npi');
        $user->cellphone = $request->input('cellphone');
        $user->sesemail = $request->input('sesemail');
        $user->ses_username = $request->input('ses_username', null);
        $user->calendar = $request->input('calendar');
        $user->address1 = $request->input('address1');
        $user->address2 = $request->input('address2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zip = $request->input('zip');
        $user->name = $request->input('firstname') . ' ' . $request->input('middlename') . ' ' . $request->input('lastname');
        $user->usertype_id = $request->input('usertype');
        $user->acc_key = $request->input('acc_key');
        $user->two_factor_auth = $request->input('two_factor_auth') ?: null;
        $user->speciality = $request->input('speciality');
        $user->provider_type_id = $request->input('provider_type_id') ?: null;

        if ($user->level > 2 && $request->input('network')) {
            foreach ($request->input('network') as $value) {
                $userNetwork = NetworkUser::firstOrCreate(['user_id' => $user->id, 'network_id' => $value]);
            }
        }

        $menuID = $request->input('landing_page');
        if ($menuID != '') {
            $user->menu_id = $menuID;
        }
        $password = $request->input('password');
        $confirmPassword = $request->input('password_confirmation');

        if ($password != '') {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        $previous_roles = Role_user::where('user_id', '=', $user->id)->get();
        $i = 0;
        $previous_roles_id = array();
        foreach ($previous_roles as $previous_role) {
            $previous_roles_id[$i] = $previous_role->role_id;
            $i++;
        }
        $i = 0;
        $new_roles = $request->input('role', []);
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
                $delete_role = Role_user::where('user_id', '=', $user->id)
                    ->where('role_id', '=', $remove_role)
                    ->delete();

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

        $action = 'updated user profile ';
        if ($password != '') {
            $action .= 'and reset password ';
        }
        $action .= 'of id ' . $id;
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
    public function destroy(Request $request)
    {
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        if (!$request->input() || $request->input() === '' || sizeof($request->input()) < 1) {
            return;
        }

        $toDelete = $request->input();
        array_pop($toDelete);

        $deactivate = User::whereIn('id', $toDelete)->where('active', 1)->update(['active' => 0]);

        if ($deactivate != 0) {
            $action = 'Deleted ' . $deactivate . ' users with ID ' .implode(", ", $toDelete);
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reactivate($userID, Request $request)
    {
        if (!policy(new User)->administration()) {
            session()->flash('failure', 'Unauthorized Access!');
            return redirect('/home');
        }
        if ($userID === '') {
            return;
        }

        $reactivate = User::where('id', $userID)->update(['active' => 1]);

        $action = 'Reactivated user with ID '. $userID;
        $description = '';
        $filename = basename(__FILE__);
        $ip = $request->getClientIp();
        Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
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

        $user = Auth::user();

        foreach ($roles as $role) {
            if (!$user->isSuperAdmin() && $role->name == 'patient-record') {
                continue;
            } elseif (!$user->isSuperAdmin() && $role->name == 'super-admin') {
                continue;
            }

            $roleArray[$role->name] = $role->display_name;
        }
        return $roleArray;
    }

    public function getUserLevels($defaultLevel = null)
    {
        $userLevelArray = array();
        if ($defaultLevel) {
            $userLevels = UserLevel::where('id', $defaultLevel)->get();
            foreach ($userLevels as $userLevel) {
                $userLevelArray[$userLevel->id] = $userLevel->name;
            }
        } else {
            $level = Auth::user()->level;
            $userLevels = UserLevel::where('id', '>=', $level)->get();
            foreach ($userLevels as $userLevel) {
                $userLevelArray[$userLevel->id] = $userLevel->name;
            }
        }

        return $userLevelArray;
    }

    public function search(Request $request)
    {
        $userID = Auth::user()->id;

        $tosearchdata = json_decode($request->input('data'), true);
        if (session('user-level') == 1) {
            $search_val = $tosearchdata['value'];
            $users = User::where(function ($query) use ($tosearchdata) {
                if (!$tosearchdata['include_deactivated']) {
                    $query->where('active', '=', '1');
                }
            })
                ->where(function ($query) use ($search_val) {
                    $query->where('firstname', 'LIKE', '%' . $search_val . '%')
                        ->orWhere('middlename', 'LIKE', '%' . $search_val . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search_val . '%');
                })
                ->get();
        } elseif (session('user-level') == 2) {
            $users = User::getUsersByName($tosearchdata['value'], $tosearchdata['include_deactivated'])->get();
        } else {
            $search_val = $tosearchdata['value'];
            $users = User::query()
                ->leftjoin('practice_user', 'users.id', '=', 'practice_user.user_id')
                ->leftjoin('practices', 'practice_user.practice_id', '=', 'practices.id')
                ->where('practice_user.practice_id', User::getPractice($userID)->id)
                ->where(function ($query) use ($search_val) {
                    $query->where('firstname', 'LIKE', '%' . $search_val . '%')
                        ->orWhere('middlename', 'LIKE', '%' . $search_val . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search_val . '%');
                })
                ->where(function ($query) use ($tosearchdata) {
                    if (!$tosearchdata['include_deactivated']) {
                        $query->where('active', '=', '1');
                    }
                })
                ->whereNotNull('practice_id')
                ->where('practice_user.practice_id', User::getPractice($userID)->id)
                ->get();
        }

        $data = [];

        $i = 0;
        foreach ($users as $user) {
            if ((session('user-level') == 3 || session('user-level') == 4) && $user->practice_id != User::getPractice($userID)->id) {
                continue;
            }
            if (session('user-level') == 1) {
                $id = $user->id;
            } else {
                $id = $user->user_id;
            }

            $user = User::find($id);

            $data[$i]['id'] = $id;
            $data[$i]['name'] = $user->lastname . ', ' . $user->firstname;
            $data[$i]['email'] = $user->email;
            $data[$i]['active'] = $user->active;
            if ($user->level) {
                $data[$i]['level'] = UserLevel::find($user->level)->name;
            } else {
                $data[$i]['level'] = 'Undefined';
            }

            $data[$i]['practice'] = 'Ocuhub';

            if ($user->userNetwork->first()) {
                $data[$i]['practice'] = $user->userNetwork->first()->network->name;
            }
            if ($practice = User::getPractice($id)) {
                $data[$i]['practice'] = $practice->name;
            }
            $i++;
        }
        return json_encode($data);
    }

    public function validateData($data, $userID = 0)
    {
        $rules = $this->validationRules($data, $userID);
        $validator = Validator::make($data, $rules);
        return $validator;
    }

    public function validationRules($data, $userID)
    {
        $validationRules = [
            'title' => 'sometimes|max:255',
            'body' => 'sometimes|required',
            'password' => 'sometimes|same:password_confirmation',
            'email' => 'sometimes|email|unique:users,email,' . $userID,
            'sesemail' => 'sometimes|email',
            'sesemail' => 'sometimes|email',
            'address1' => 'sometimes|max:120',
            'address2' => 'sometimes|max:120',
            'city' => 'sometimes|max:20',
            'state' => 'sometimes|max:20',
            'zip' => 'sometimes|numeric',
        ];
        return $validationRules;
    }

    public function getLandingPagebyRole(Request $request)
    {
        $menuData = [];
        $menuData['care-console'] = ['6', 'Care Console'];
        $menuData['administrator'] = ['7', 'Administration'];

        return json_encode($menuData);
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
        $user->name = $request->input('firstname') . ' ' . $user->middlename . ' ' . $request->input('lastname');
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
}
