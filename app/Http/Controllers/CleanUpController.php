<?php

namespace myocuhub\Http\Controllers;

use Illuminate\Http\Request;

use myocuhub\Http\Requests;
use myocuhub\Http\Controllers\Traits\CleanUp;

class CleanUpController extends Controller
{
    use CleanUp;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!policy(new \myocuhub\Models\Careconsole)->accessConsole()) {
            $request->session()->flash('failure', 'Unauthorized Access to the CleanUp Module. Please contact your administrator.');
            return redirect('/');
        }
        $data = array();
        return view('admin.cleanup.index')->with('data', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showlist(Request $request)
    {
        $value = $request->input('value');
        $filter = $request->input('filter');
        $list = $this->getCLeanUpList($value, $filter);
        return json_encode($list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cleanlist(Request $request)
    {
        $list = $request->input('list');
        $correctedValue = $request->input('correctedValue');
        $filter = $request->input('filter');
        $update = $this->cleanData($list, $correctedValue, $filter);
        return $update;
    }
}
