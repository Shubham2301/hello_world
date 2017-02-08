<?php

namespace myocuhub\Models;

use Illuminate\Database\Eloquent\Model;
use myocuhub\Facades\Helper;

class FPCWritebackAudit extends Model
{
    protected $table = '4pc_writeback_audit';

    public function patient()
    {
        return $this->belongsTo('myocuhub\Patient');
    }

    public function appointments()
    {
        return $this->belongsTo('myocuhub\Models\Appointment', 'appointment_id');
    }

    public function provider()
    {
        return $this->belongsTo('myocuhub\User', 'provider_id');
    }

    public static function WritebackReport($networkId = null, $startDate, $endDate)
    {
        $startDate = Helper::formatDate($startDate, config('constants.db_date_format'));
        $endDate = Helper::formatDate($endDate, config('constants.db_date_format'));

        $query = self::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->has('patient')
            ->has('appointments');
        if ($networkId) {
            $query
                ->whereHas('patient.careConsole.importHistory', function ($subquery) use ($networkId) {
                    $subquery->where('network_id', $networkId);
                });
        }

        $query
            ->with(['appointments', 'patient', 'provider', 'patient.careConsole.importHistory.network']);

        return $query->get();
    }
}
