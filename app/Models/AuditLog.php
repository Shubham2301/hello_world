<?php

namespace myocuhub\Models;

use Datetime;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{

    public static function Reports($networkId = null, $startDate, $endDate)
    {

        $start_date = new Datetime($startDate);
        $end_date = new Datetime($endDate);
        $end_date->modify("+1 days");

        return self::query()
            ->leftjoin('users', 'audit_logs.user_id', '=', 'users.id')
            ->leftjoin('network_user', 'users.id', '=', 'network_user.user_id')
            ->leftjoin('networks', 'network_user.network_id', '=', 'networks.id')
            ->where(function ($query) use ($networkId) {
                if ($networkId != null) {
                    $query->where('network_user.network_id', $networkId);
                }
            })
            ->where('audit_logs.created_at', '>=', $start_date->format('Y-m-d 00:00:00'))
            ->where('audit_logs.created_at', '<=', $end_date->format('Y-m-d 00:00:00'))
            ->orderBy('audit_logs.created_at', 'desc')
            ->get(['users.name as user_fname', 'users.lastname as user_lname', 'users.id as user_id', 'networks.name as network_name', 'networks.id as network_id', 'audit_logs.action', 'audit_logs.created_at', 'audit_logs.ip as ip']);
    }
    
}
