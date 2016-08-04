<?php

namespace myocuhub;

use Auth;
use Illuminate\Database\Eloquent\Model;
use myocuhub\Jobs\PatientEngagement\PostAppointmentPatientMail;
use myocuhub\Jobs\PatientEngagement\PostAppointmentPatientPhone;
use myocuhub\Jobs\PatientEngagement\PostAppointmentPatientSMS;
use Illuminate\Database\Eloquent\SoftDeletes;
use myocuhub\Models\PracticeUser;
use myocuhub\Network;
use myocuhub\Models\PatientFile;
use myocuhub\Models\Careconsole;

class Patient extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
    'title',
    'firstname',
    'middlename',
    'lastname',
    'workphone',
    'homephone',
    'cellphone',
    'email',
    'addressline1',
    'addressline2',
    'city',
    'zip',
    'lastfourssn',
    'birthdate',
    'gender',
    'insurancecarrier',
    'country',
    'preferredlanguage',
    'state',
    'special_request',
    'pcp'
    ];

    public function getPhone()
    {
        $phone = '-';

        if ($this->cellphone != '' || $this->cellphone != null) {
            $phone = $this->cellphone;
        } elseif ($this->homephone != '' || $this->homephone != null) {
            $phone = $this->homephone;
        } elseif ($this->workphone != '' || $this->workphone != null) {
            $phone = $this->workphone;
        }

        return $phone;
    }

    public function getName(){
        return $this->lastname . ', ' . $this->firstname;
    }

    public static function getPatients($filters, $sortInfo = [], $countResult)
    {
        $columns = [
            'patients.id',
            'patients.firstname',
            'patients.lastname',
            'patients.email',
            'patients.cellphone',
            'patients.lastfourssn',
            'patients.addressline1',
            'patients.addressline2',
            'patients.city',
            'patients.birthdate'
        ];

        $query = self::where(function ($query) use ($filters) {
            foreach ($filters as $filter) {
                $query->where(function ($query) use ($filter) {

                    switch ($filter['type']) {
                        case 'name':
                            $query->where('firstname', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $filter['value'] . '%');
                            break;

                        case 'ssn':
                            $query->where('lastfourssn', $filter['value']);
                            break;
                        case 'email':
                            $query->where('email', 'LIKE', '%' . $filter['value'] . '%');
                            break;
                        case 'phone':
                            $query->where('cellphone', 'LIKE', '%' . $filter['value'] . '%');
                            break;
                        case 'address':
                            $query->where('city', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('addressline1', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('addressline2', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('country', 'LIKE', '%' . $filter['value'] . '%');
                            break;
                        case 'id':
                            $query->Where('patients.id', $filter['value']);
                            break;
                        case 'all':
                            $query->where('firstname', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('lastfourssn', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('city', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('addressline1', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('addressline2', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('country', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('cellphone', 'LIKE', '%' . $filter['value'] . '%')
                            ->orWhere('email', 'LIKE', '%' . $filter['value'] . '%');

                            break;
                    }
                });
            }
        });

        if (session('user-level') == 1) {
            $query
                ->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id');
        } elseif (session('user-level') == 2) {
            $query
                ->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id')
                ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
                ->where('import_history.network_id', session('network-id'));
        } else {
            $practiceUser= PracticeUser::where('user_id', Auth::user()->id)->first();
            $query
                ->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id')
                ->leftjoin('practice_patient', 'patients.id', '=', 'practice_patient.patient_id')
                ->where('practice_patient.practice_id', $practiceUser['practice_id']);
        }

        if (!isset($sortInfo['order'])) {
            $sortInfo['order']='SORT_ASC';
            $sortInfo['field']='lastname';
        } elseif (!$sortInfo['order']) {
            $sortInfo['order']='SORT_ASC';
            $sortInfo['field']='lastname';
        }

        $toSort['SORT_ASC'] = 'asc';
        $toSort['SORT_DESC'] = 'desc';

        return
            $query
            ->orderBy($sortInfo['field'], $toSort[$sortInfo['order']])
			->paginate($countResult, $columns);
    }

    public static function getPatientsByName($name)
    {
        return self::query()
            ->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id')
            ->leftjoin('import_history', 'careconsole.import_id', '=', 'import_history.id')
            ->where('import_history.network_id', session('network-id'))
            ->where(function ($query) use ($name) {
                $query->where('firstname', 'LIKE', '%' . $name . '%')
                ->orWhere('middlename', 'LIKE', '%' . $name . '%')
                ->orWhere('lastname', 'LIKE', '%' . $name . '%');
            })
            ->get(['*', 'patients.id']);
    }

    public static function getPreviousProvider($patientID)
    {
        return self::where('patients.id', $patientID)
            ->leftjoin('appointments', 'patients.id', '=', 'appointments.patient_id')
            ->orderBy('start_datetime', 'DESC')
            ->leftjoin('users', 'appointments.provider_id', '=', 'users.id')
            ->leftjoin('practices', 'appointments.practice_id', '=', 'practices.id')
            ->first();
    }

    public static function getColumnNames()
    {
        $patients = \Schema::getColumnListing('patients');
        $dummy_array = array_fill_keys(array_keys($patients), null);
        return array_combine($patients, $dummy_array);
    }

    public static function getPreviousProvidersList($patientID)
    {
        return self::where('patients.id', $patientID)
            ->leftjoin('appointments', 'patients.id', '=', 'appointments.patient_id')
            ->orderBy('start_datetime', 'DESC')
            ->leftjoin('users', 'appointments.provider_id', '=', 'users.id')
            ->leftjoin('practices', 'appointments.practice_id', '=', 'practices.id')
            ->leftjoin('practice_location', 'appointments.location_id', '=', 'practice_location.id')
            ->groupBy('users.id')
            ->get();
    }

    public function engagementPreference(){
        return $this->hasOne('myocuhub\Models\EngagementPreference');
    }

    public function engagePatient($appt){
        switch ($appt['patient_preference']) {
            case config('patient_engagement.type.sms'):
                dispatch((new PostAppointmentPatientSMS($appt))->onQueue('sms'));
                break;
            case config('patient_engagement.type.phone'):
                dispatch((new PostAppointmentPatientPhone($appt))->onQueue('phone'));
                break;
            case config('patient_engagement.type.email'):
            default:
                dispatch((new PostAppointmentPatientMail($appt))->onQueue('email'));
                break;
        }
    }

    public function network(){
        $network = $this->where('patients.id', $this->id)
                    ->leftjoin('careconsole', 'patients.id', '=', 'careconsole.patient_id')
                    ->leftjoin('import_history', 'careconsole.import_id' , '=' , 'import_history.id')
                    ->first(['import_history.network_id']);
        return Network::find($network['network_id']);
    }
    
    public function getLocation()
    {
        $address = urlencode($this->addressline1.' '.$this->addressline2.' '.$this->city.' '.$this->state.' '.$this->zip.' '.$this->country);
        $data = [];
        $data['latitude'] = '';
        $data['longitude'] = '';
        try {
            $patientLocation = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.env('MAP_API_KEY')), true);
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }
        if (isset($patientLocation['results'][0]['geometry']['location']['lat'])) {
            $data['latitude'] = $patientLocation['results'][0]['geometry']['location']['lat'];
            $data['longitude'] = $patientLocation['results'][0]['geometry']['location']['lng'];
        }
        return $data;
    }
    public function files()
    {
        return $this->hasMany(PatientFile::class);
    }

    public function careConsole() {
        return $this->hasOne(Careconsole::class);
    }
}
