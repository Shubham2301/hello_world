<?php

namespace myocuhub\Http\Controllers\CareConsole;

use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\CareconsoleStage;
use myocuhub\Network;
use myocuhub\Services\KPI\KPIService;

class CareConsoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $KPIService;

    public function __construct(KPIService $KPIService)
    {
        $this->KPIService = $KPIService;
    }

    public function index()
    {
        $networkID = 1;
        $careconsoleStages = Network::find($networkID)->careconsoleStages;
        $overview = array();
        $i = 0;
        foreach ($careconsoleStages as $stage) {
            $overview['stages'][$i]['id'] = $stage->stage_id;
            $overview['stages'][$i]['name'] = $stage->name;
            $overview['stages'][$i]['display_name'] = $stage->display_name;
            $overview['stages'][$i]['description'] = $stage->description;

            $kpis = CareconsoleStage::find($stage->stage_id)->kpi;
            $j = 0;
            foreach ($kpis as $kpi) {
                $overview['stages'][$i]['kpis'][$j]['id'] = $kpi->id;
                $overview['stages'][$i]['kpis'][$j]['name'] = $kpi->name;
                $overview['stages'][$i]['kpis'][$j]['display_name'] = $kpi->display_name;
                $overview['stages'][$i]['kpis'][$j]['color_indicator'] = $kpi->color_indicator;
                $overview['stages'][$i]['kpis'][$j]['description'] = $kpi->description;
                $overview['stages'][$i]['kpis'][$j]['count'] = $this->KPIService->getCount($kpi->name, $networkID, $stage->stage_id);
                $j++;
            }
            $overview['stages'][$i]['kpi_count'] = $j;
            $i++;
        }
        return view('careconsole.index')->with('overview', $overview);
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
    public function show($id)
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
}
