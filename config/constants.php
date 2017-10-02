<?php

return [

    'support' => [
        'email_id' => 'support@illumacc.com',
        'email_name' => 'illuma',
        'phone' => '844-605-8243',
        'contact_form' => 'www.illumacc.com/contact',
        'application_error' => 'applicationerror@illumcc.com',
        'ses' => [
            'email' => [
                'id' => 'Admin2@direct.ocuhub.com',
                'display_name' => 'Support illuma',
            ],
        ],
    ],
    'onboarding_notification' => [
        'email_id' => 'ehoell@illumacc.com',
        'email_name' => 'Eric'
    ],
    'production_url' => 'https://myocuhub.com',
    'message_type' => [
        'email' => 1,
        'phone' => 2,
        'sms' => 3,
    ],
    'message_stage' => [
        'request_for_appointment' => 1,
        'confirm_appointment' => 2,
        'post_appointment' => 3,
    ],
    'message_views' => [
        'request_appointment_provider' => [
            'subject' => 'Request for Appointment',
            'view' => 'emails.appt-confirmation-provider',
        ],
        'request_appointment_patient' => [
            'subject' => 'Appointment has been scheduled',
            'view' => 'emails.appt-confirmation-patient',
        ],
        'post_appointment_patient' => [
            'subject' => 'How was your Appointment',
            'view' => 'emails.post-appointment-patient',
        ],
        'send_record_provider' => [
            'subject' => 'New health record created',
            'view' => 'You have created a new health record',
        ],
        'first_appointment_notification' => [
            'subject' => 'First appointment for practice scheduled',
            'view' => 'emails.first-appt-notification',
            'name' => 'illuma Appointment Notification',
            'email' => 'appointments@direct.ocuhub.com',
        ],

    ],
    'language' => [
        'english' => 1,
        'spanish' => 2,
    ],
    'gender' => [
        'male' => 'M',
        'female' => 'F',
    ],
    'gender_variations' => [
        'male' => 'M',
        'm' => 'M',
        'female' => 'F',
        'f' => 'F',
    ],
    'paths' => [
        'ccda' => [
            'temp_ccda' => base_path() . '/temp_ccda/',
            'temp_json' => base_path() . '/temp_ccda/temp_json/',
            'default_ccda' => public_path() . '/lib/ccda/patient-demographics.json',
            'tojson' => public_path() . '/js/tojson.js ',
            'toxml' => public_path() . '/js/toxml.js ',
            'stylesheet' => public_path() . '/lib/xslt/CDA.xsl',
        ],

        'pdf' => [
            'temp_dir' => base_path() . '/temp_ccda/',
            'ext' => '.pdf',
        ],

        'signature_files' => public_path().'/lib/digital-signature/',
    ],
    'fpc_mandatory_fields' => [
        'birthdate' => [
            'display_name' => 'Date of Birth',
            'field_name' => 'birthdate',
            'type' => 'field_date',
        ],
    ],
    'two_factor_auth' => [
        'validity_limit' => 5,
    ],
    'provider_list_display_limit' => '5',
    'practice_location_display_limit' => '3',
    'btn_urls' => [
        'from_schedule' => [
            'back_btn' => 'back_to_select_patient_btn',
            'save_url' => '/administration/patients/add',
        ],
        'from_admin' => [
            'back_btn' => 'back_to_admin_patient_btn',
            'save_url' => '/administration/patients/add',

        ],
    ],
    'default_paginate_result' => 20,
    'default_timeline_result' => 5,
    'default_careconsole_paginate' => 50,
    'date_time_format' => [
        'date_time' => 'F j Y, g:i a',
        'date_only' => 'F j Y',
    ],
    'date_format' => 'm/d/Y',
    'db_date_format' => 'Y-m-d h:i:s',
    'report_date_format' => [
        'start_date_time' => 'Y-m-d 00:00:00',
        'end_date_time' => 'Y-m-d 23:59:59'
    ],
    'schedule_notes_delimiter' => '</br>',

    'user_levels' => [
        'network' => 'Network',
        'practice' => 'Practice',
        'location' => 'Location',
    ],

    'providerNearPatient' => [
        'providerRadius' => 150,
        'providerNumber' => 20,
    ],

    'google_map_url' => 'http://www.google.com/maps/place',

    'default_timezone' => "UTC-5",

    'import_type'  => [
        'admin' => 0,
        'bulk_import' => 1,
        '4PC_writeback' => 2,
    ],

    'providerNearPatient' => [
        'providerRadius' => 150,
        'providerNumber' => 20,
    ],
];
