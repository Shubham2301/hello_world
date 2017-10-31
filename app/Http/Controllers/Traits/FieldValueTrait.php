<?php

namespace myocuhub\Http\Controllers\Traits;

use myocuhub\Facades\Helper;
use myocuhub\Models\Appointment;
use myocuhub\Models\Careconsole;
use myocuhub\Models\ContactHistory;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Patient;
use myocuhub\User;

trait FieldValueTrait
{
    protected function getFieldValue($input, $field)
    {
        if ($input instanceof Careconsole) {
            return self::getCareConsoleValues($input, $field);
        } elseif ($input instanceof ContactHistory) {
            return self::getContactHistoryFieldValues($input, $field);
        } elseif ($input instanceof Patient) {
            return self::getPatientFieldValues($input, $field);
        } elseif ($input instanceof Appointment) {
            return self::getAppointmentFieldValues($input, $field);
        } elseif ($input instanceof Practice) {
            return self::getPracticeFieldValues($input, $field);
        } elseif ($input instanceof User) {
            return self::getUserFieldValues($input, $field);
        }

        return '-';
    }

    private function getCareConsoleValues(Careconsole $careconsole, $field)
    {
        switch ($field) {
            case 'patient_name':
            case 'patient-name':
            case 'subscriber-id':
                return $careconsole->patient ? self::getPatientFieldValues($careconsole->patient, $field) : '-';
                break;
            case 'appointment_date':
            case 'appointment-date':
            case 'appointment_type':
            case 'provider-name':
            case 'scheduled_to_provider':
            case 'scheduled_to_practice_location':
            case 'practice_name':
            case 'scheduled_to_practice':
            case 'appointment-status':
            case 'clinical-findings-status':
                return $careconsole->appointment ? self::getAppointmentFieldValues($careconsole->appointment, $field) : '-';
                break;
            }

        return '-';
    }

    private function getContactHistoryFieldValues(ContactHistory $contact_history, $field)
    {
        switch ($field) {
            case 'patient_name':
            case 'patient-name':
            case 'subscriber-id':
                return $contact_history->careconsole ? self::getCareConsoleValues($contact_history->careconsole, $field) : '-';
                break;
            case 'appointment_date':
            case 'appointment-date':
            case 'appointment_type':
            case 'provider-name':
            case 'scheduled_to_provider':
            case 'scheduled_to_practice_location':
            case 'practice_name':
            case 'scheduled_to_practice':
            case 'appointment-status':
            case 'clinical-findings-status':
                return $contact_history->appointments ? self::getAppointmentFieldValues($contact_history->appointments, $field) : '-';
                break;
            case 'action_name':
                return $contact_history->action ? $contact_history->action->display_name : '';
                break;
            case 'action_result_name':
                return $contact_history->actionResult ? $contact_history->actionResult->display_name : '';
                break;
            case 'action_date_time':
                return Helper::formatDate($contact_history->created_at, config('constants.date_time')) ?: '-';
                break;
            case 'notes':
                $note = trim($contact_history->notes);
                $note = str_replace('</br>', ' ', $note);
                return $note;
                return ;
                break;
            case 'user_name':
                return $contact_history->users ? self::getUserFieldValues($contact_history->users, $field) : '-';
                break;
            }

        return '-';
    }

    private function getPatientFieldValues(Patient $patient, $field)
    {
        switch ($field) {
            case 'patient_name':
            case 'patient-name':
                return $patient->getName('print_format');
                break;
            case 'subscriber-id':
                $insurance = $patient->patientInsurance;
                return $insurance ? $insurance->subscriber_id : '-';
                break;
            }

        return '-';
    }

    private function getAppointmentFieldValues(Appointment $appointment, $field)
    {
        switch ($field) {
            case 'patient_name':
            case 'patient-name':
            case 'subscriber-id':
                return $appointment->patient ? self::getPatientFieldValues($appointment->patient, $field) : '-';
                break;
            case 'appointment_date':
            case 'appointment-date':
                return Helper::formatDate($appointment->start_datetime, config('constants.date_time')) ?: '-';
                break;
            case 'appointment_type':
                return $appointment->appointmenttype;
                break;
            case 'provider-name':
            case 'scheduled_to_provider':
                return $appointment->provider ? self::getUserFieldValues($appointment->provider, $field) : '-';
                break;
            case 'scheduled_to_practice_location':
                return $appointment->withDeletedPracticeLocation ? $appointment->withDeletedPracticeLocation->locationname : '-';
                break;
            case 'practice_name':
            case 'scheduled_to_practice':
                return $appointment->practice ? self::getPracticeFieldValues($appointment->practice, $field) : '-';
                break;
            case 'clinical-findings-status':
                return ($appointment->appointmentStatus && $appointment->appointmentStatus->name == 'ready-to-be-completed') ? 'Yes' : 'No';
                break;
            case 'appointment-status':
                $appointment_status = $appointment->appointmentStatus ? $appointment->appointmentStatus->name : '';
                switch ($appointment_status) {
                    case 'cancelled':
                    case 'no-show':
                        return 'Missed';
                        break;
                    case 'waiting-for-report':
                    case 'ready-to-be-completed':
                        return 'Confirmed';
                        break;
                    default:
                        return 'Pending';
                }
                break;
            }

        return '-';
    }

    private function getPracticeFieldValues(Practice $practice, $field)
    {
        switch ($field) {
            case 'practice_name':
            case 'scheduled_to_practice':
                return $practice->name;
                break;
            case 'practice_first_appointment_date':
                $first_appointment = $practice->appointment->first();
                return $first_appointment ? self::getAppointmentFieldValues($first_appointment, 'appointment-date') : '-';
                break;
            case 'manually_added':
                return $practice->manually_created ? 'Yes' : 'No';
                break;
            case 'appointment_count':
                return $practice->appointment_count;
                break;
            case 'practice_state':
                $state = array();
                $locations = $practice->locations;
                foreach ($locations as $location) {
                    $state[] = trim(strtoupper($location->state));
                }
                $state = array_unique($state);
                return implode('; ', $state);
                break;
            case 'location_names':
                $location_names = array();
                $locations = $practice->locations;
                foreach ($locations as $location) {
                    $location_names[] = trim($location->locationname);
                }
                return implode('; ', $location_names);
                break;
            case 'loc':
                $loc = array();
                $locations = $practice->locations;
                foreach ($locations as $location) {
                    $loc[] = trim(strtoupper($location->location_code));
                }
                $loc = array_unique($loc);
                return implode('; ', $loc);
                break;
            case 'location_count':
                return $practice->locations->count();
                break;
            case 'practice_networks':
                $network = array();
                $network_list = $practice->practiceNetwork;
                foreach ($network_list as $network_info) {
                    $network[] = trim($network_info->network->name);
                }
                return implode('; ', $network);
                break;
            case 'provider_count':
                return $practice->practiceUsers->count();
                break;
            case 'contract_start_date':
                $first_network_creation_date = $practice->practiceNetwork->first()->network->created_at;
                $practice_creation_date = $practice->created_at;

                if ($first_network_creation_date->gt($practice_creation_date)) {
                    $contract_start_date = $first_network_creation_date;
                } else {
                    $contract_start_date  = $practice_creation_date;
                }
                return $contract_start_date->addDays(60)->modify('first day of next month')->toFormattedDateString();
                break;
            case 'contract_cancelled_date':
                return $practice->deleted_at ? $practice->deleted_at->toFormattedDateString() : '-';
                break;
            case 'practice_discount':
                return $practice->discount ?: '-';
                break;
            case 'provider_names':
                $provider_name = array();
                $providers = $practice->practiceUsers;
                foreach ($providers as $provider) {
                    $provider_name[] = self::getUserFieldValues($provider->user, 'provider-name');
                }
                return implode('; ', $provider_name);
                break;
        }

        return '-';
    }

    private function getUserFieldValues(User $user, $field)
    {
        switch ($field) {
            case 'provider-name':
            case 'scheduled_to_provider':
            case 'user_name':
                return $user->getName('print_format');
                break;
            }

        return '-';
    }
}
