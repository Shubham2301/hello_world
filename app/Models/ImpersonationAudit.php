<?php

namespace myocuhub\Models;

use Datetime;
use Illuminate\Database\Eloquent\Model;

class ImpersonationAudit extends Model
{
    protected $table = 'impersonation_audit';

     public static function ImpersonationReport($networkId = null, $startDate, $endDate)
    {

        $start_date = new Datetime($startDate);
        $end_date = new Datetime($endDate);
        $end_date->modify("+1 days");

        return self::query()
            ->leftjoin('users', 'impersonation_audit.logged_in_user_id', '=', 'users.id')
            ->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
            ->leftjoin('networks', 'network_user.network_id', '=', 'networks.id')
            ->where(function ($query) use ($networkId) {
                if ($networkId != null) {
                    $query->where('network_user.network_id', $networkId);
                }
            })
            ->where('impersonation_audit.created_at', '>=', $start_date->format('Y-m-d 00:00:00'))
            ->where('impersonation_audit.created_at', '<=', $end_date->format('Y-m-d 00:00:00'))
            ->orderBy('impersonation_audit.created_at', 'desc')
            ->get(['users.name as user_fname', 'users.lastname as user_lname', 'users.id as impersonated_user_id', 'networks.name as network_name', 'networks.id as network_id', 'impersonation_audit.created_at', 'impersonation_audit.user_impersonated_id as impersonated_id']);
    }
}
