<?php

return [

    'archive' => [
		'closed' => 1,
		'incomplete' => 0,
    ],

    'patient_type' => [
        'new' => 1,
        'old' => 0,
    ],

    'appointment_completed' => [
        'show' => 1,
        'no_show' => 0,
    ],

    'call_center_report' => [
        'graph_legends' => [
            'Date' => '',
            'Scheduled' => '#22b573',
            'Attempted' => '#0071bc',
        ],
    ],

    'appointment_status' => [
        'scheduled_appointment_existing_relationship' => 1,
        'scheduled_appointment_non_existing_relationship' => 2,
        'past_appointment_existing_relationship' => 3,
        'past_appointment_non_existing_relationship' => 4,
    ],
];
