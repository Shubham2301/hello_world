<?php

namespace myocuhub\Http\Controllers\Traits;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use myocuhub\Models\Appointment;
use myocuhub\Models\AppointmentType;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\ReferralHistory;

trait DuplicateMaintenance
{
	public function getCLeanUpList($value, $filter)
	{
		$user = Auth::user();
        $network = $user->network;
		$networkID = $network->network_id;
		$list = array();
		switch ($filter) {
			case 'referred_by_practice':
				$list = ReferralHistory::where('referred_by_practice', 'LIKE', '%' . strtolower($value) . '%')
					->whereHas('careConsole.importHistory', function ($query) use ($networkID) {
			            $query->where('network_id', $networkID);
			        })
			        ->distinct()
			       	->get(['referred_by_practice as list_item'])
			       	->toArray();
				break;
			case 'referred_by_provider':
				$list = ReferralHistory::where('referred_by_provider', 'LIKE', '%' . strtolower($value) . '%')
					->whereHas('careConsole.importHistory', function ($query) use ($networkID) {
			            $query->where('network_id', $networkID);
			        })
					->distinct()
					->get(['referred_by_provider as list_item'])
					->toArray();
				break;
			case 'disease_type':
				$list = ReferralHistory::where('disease_type', 'LIKE', '%' . strtolower($value) . '%')
					->whereHas('careConsole.importHistory', function ($query) use ($networkID) {
	                    $query->where('network_id', $networkID);
	                })
					->distinct()
					->get(['disease_type as list_item'])
					->toArray();
				break;
			case 'appointment_types':
				$list = Appointment::where('appointmenttype', 'LIKE', '%' . strtolower($value) . '%')
					->whereHas('patient.careConsole.importHistory', function ($query) use ($networkID) {
	                    $query->where('network_id', $networkID);
	                })
					->distinct()
					->get(['appointmenttype as list_item'])
					->toArray();
				break;
			case 'manual_appointment_types':
				$list = AppointmentType::where('display_name', 'LIKE', '%' . strtolower($value) . '%')
					->where('network_id', session('network-id'))
					->distinct()
					->get(['display_name as list_item'])
					->toArray();
				break;
			case 'insurance_details':
				$list = PatientInsurance::where('insurance_carrier', 'LIKE', '%' . strtolower($value) . '%')
					->whereHas('patient.careConsole.importHistory', function ($query) use ($networkID) {
	                    $query->where('network_id', $networkID);
	                })
					->distinct()
					->get(['insurance_carrier as list_item'])
					->toArray();
				break;
			default:
				break;
		}

		usort($list, 'self::cmp');
		return $list;
	}

	public function cleanData ($list, $correctedValue, $filter) {

		$user = Auth::user();
        $network = $user->network;
		$networkID = $network->network_id;
		switch ($filter) {
			case 'referred_by_practice':
				foreach ($list as $key => $item) {
					$update = ReferralHistory::where('referred_by_practice', $item)
						->whereHas('careConsole.importHistory', function ($query) use ($networkID) {
		                    $query->where('network_id', $networkID);
		                })
		                ->update(['referred_by_practice' => $correctedValue]);
				}
				break;
			case 'referred_by_provider':
				foreach ($list as $key => $item) {
					$update = ReferralHistory::where('referred_by_provider', $item)
						->whereHas('careConsole.importHistory', function ($query) use ($networkID) {
			                $query->where('network_id', $networkID);
			            })
			            ->update(['referred_by_provider' => $correctedValue]);
		        }
				break;
			case 'disease_type':
				foreach ($list as $key => $item) {
					$update = ReferralHistory::where('disease_type', $item)
						->whereHas('careConsole.importHistory', function ($query) use ($networkID) {
			                $query->where('network_id', $networkID);
			            })
			            ->update(['disease_type' => $correctedValue]);
		        }
				break;
			case 'appointment_types':
				foreach ($list as $key => $item) {
					$update = Appointment::where('appointmenttype', $item)
						->whereHas('patient.careConsole.importHistory', function ($query) use ($networkID) {
			                $query->where('network_id', $networkID);
			            })
			            ->update(['appointmenttype' => $correctedValue]);
				}
				break;
			case 'manual_appointment_types':
				foreach ($list as $key => $item) {
			        $update = AppointmentType::where('display_name', $item)
			        	->where('network_id', session('network-id'))
			        	->where('type', 'ocuhub')
			        	->delete();
				}
				if ( !(AppointmentType::where('name', strtolower(str_replace(' ', '_', $correctedValue)))->where('network_id', session('network-id'))->where('type', 'ocuhub')->first()) ) {
					$appointment_type = new AppointmentType();
	                $appointment_type->name = strtolower(str_replace(' ', '_', $correctedValue));
	                $appointment_type->display_name = $correctedValue;
	                $appointment_type->type = 'ocuhub';
	                $appointment_type->network_id = session('network-id');
	                $appointment_type->save();
            	}
				break;
			case 'insurance_details':
				foreach ($list as $key => $item) {
					$update = PatientInsurance::where('insurance_carrier', $item)
						->whereHas('patient.careConsole.importHistory', function ($query) use ($networkID) {
			                $query->where('network_id', $networkID);
			            })
			            ->update(['insurance_carrier' => $correctedValue]);
			        }
				break;
			default:
				break;
		}

		return '1';
	}

	private static function cmp($a, $b)
    {
        return strcasecmp (trim($a["list_item"]), trim($b["list_item"]));
    }
}
