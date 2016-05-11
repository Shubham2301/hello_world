<?php

namespace myocuhub\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Menu;
use myocuhub\Patient;
use myocuhub\Role_user;
use myocuhub\User;

class ScriptsController extends Controller
{
    public function cleanUpPhoneNumbers()
    {
        $patients = Patient::where('workphone', 'LIKE', '%E9%')
                            ->orWhere('homephone', 'LIKE', '%E9%')
                            ->orWhere('cellphone', 'LIKE', '%E9%')
							->get();

        foreach ($patients as $patient) {
            $workphone = $patient->workphone;
            $homephone = $patient->homephone;
            $cellphone = $patient->cellphone;
            try {
                if ($workphone) {
                    $patient->workphone = (float)$workphone;
                }
                if ($homephone) {
                    $patient->homephone = (float)$homephone;
                }

                if ($cellphone) {
                    $patient->cellphone = (float)$cellphone;
                }
            } catch (\Exception $e) {
            }
            $patient->save();
        }
    }

	public function getCoordinatesOFLocations()
	{
		$locations = PracticeLocation::all();
		foreach ($locations as $location) {
			if ($location->latitude != null) {
				continue;
			}
			$address = urlencode($location->addressline1.' '.$location->addressline2.' '.$location->city.' '.$location->zip.' '.$location->state);
			try {
				$json = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.env('MAP_API_KEY')), true);
			} catch (Exception $e) {
				dd($e->getMessage());
			}
			if (isset($json['results'][0]['geometry']['location']['lat'])) {
				$location->latitude = $json['results'][0]['geometry']['location']['lat'];
				$location->longitude = $json['results'][0]['geometry']['location']['lng'];
			}
			$location->save();
		}
	}
}
