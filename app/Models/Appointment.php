<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider_id', 'practice_id', 'location_id', 'patient_id', 'network_id', 'appointmenttype_key', 'appointmenttype', 'start_datetime', 'enable_writeback' , 'existing_relationship'];

    /**
     * @return mixed
     */
    public function provider()
    {
        return $this->belongsTo('myocuhub\User', 'provider_id');
    }

    /**
     * @return mixed
     */
    public function practice()
    {
        return $this->belongsTo('myocuhub\Models\Practice', 'practice_id');
    }

    /**
     * @return mixed
     */
    public function patient()
    {
        return $this->belongsTo('myocuhub\Patient');
    }

    /**
     * @return mixed
     */
    public function practiceLocation()
    {
        return $this->belongsTo('myocuhub\Models\PracticeLocation', 'location_id');
    }

    /**
     * @return mixed
     */
    public function withDeletedPracticeLocation()
    {
        return $this->belongsTo('myocuhub\Models\PracticeLocation', 'location_id')->withTrashed();
    }
    /**
     * @return mixed
     */
    public function appointmentStatus()
    {
        return $this->belongsTo('myocuhub\Models\Kpi', 'appointment_status');
    }

    public static function schedule($attributes)
    {
        $appointment = Appointment::create($attributes);

        if ($appointment) {
            return $appointment;
        } else {
            return false;
        }
    }

    public function setFPCID($apptID)
    {
        $this->fpc_id = $apptID;
        $this->save();
    }

    public static function pastAppointmentsForEngagement()
    {
        $format = 'Y-m-d H:i:s';
        return self::where('appointments.active', '1')
            ->where('appointments.start_datetime', '>', date($format, strtotime('yesterday 18:00')))
            ->where('appointments.start_datetime', '<', date($format, strtotime('today 18:00')))
            ->leftjoin('engagement_preferences', 'appointments.patient_id', '=', 'engagement_preferences.patient_id')
            ->get([
                    'appointments.id as id',
                    'appointments.patient_id as patient_id',
                    'engagement_preferences.type as patient_preference',
                ]);
    }


    protected static function getPracticeAppointmentInformation($practice_id, $network_id)
    {
        $query = self::query();
        if ($practice_id != 'all') {
            $query->where('practice_id', $practice_id);
            $query->has('patient');
        } else {
            $query->whereHas('patient', function ($sub_query) use ($network_id) {
                $sub_query->whereHas('careconsole', function ($sub_sub_query) use ($network_id) {
                    $sub_sub_query->networkCheck($network_id);
                });
            });
        }
        
        $query->whereNotNull('enable_writeback');
        $query->with(['patient', 'withDeletedPracticeLocation', 'provider']);
        $query->orderBy('start_datetime', 'desc');
        return $query->get();
    }
}
