<?php 

return [

    'support' => [
        'email_id' => 'support@ocuhub.com',
        'email_name' => 'Ocuhub',
        'phone' => '844-605-8243',
        'contact_form' => 'www.ocuhub.com/contact',
        'application_error' => 'applicationerror@ocuhub.com',
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
            'view' => 'emails.appt-confirmation-provider'
        ],
        'request_appointment_patient' => [
            'subject' => 'Appointment has been scheduled',
            'view' => 'emails.appt-confirmation-patient'
        ],
    ]
    ,
    'language' => [
        'english' => 1,
        'spanish' => 2,
    ],
    'appointment_types' => [
        'Annual Eye Exam',
        'Comprehensive Eye Exam',
        'Diabetic Eye Exam',
        'General Eye Exam',
        'ABIorVTeval',
        'UnknownEncounterReschedule',
    ],
    'paths' => [
        'ccda'=>[
            'temp_ccda' => base_path() . '/temp_ccda/',
            'temp_json' => base_path() . '/temp_ccda/temp_json/',
            'default_ccda' => public_path() .'/lib/ccda/patient-demographics.json',
            'tojson' => public_path() . '/js/tojson.js ',
            'toxml' => public_path() . '/js/toxml.js ',
            'stylesheet' => public_path() . '/lib/xslt/CDA.xsl',

        ],
    ],
    'fpc_mandatory_fields' => [
      'birthdate' => [
        'display_name' => 'Date of Birth',
        'field_name'  => 'birthdate',
        'type' => 'field_date'
      ],
    ],
    'two_factor_auth' => [
        'validity_limit' => 5,
    ],
    'provider_list_display_limit' => '5',
    'practice_location_display_limit' => '3',

   'btn_urls' =>[
      'from_schedule'=> [
         'back_btn' => 'back_to_select_patient_btn',
         'save_url' => '/administration/patients/add'
      ],
      'from_admin' =>[
          'back_btn' => 'back_to_admin_patient_btn',
          'save_url' => '/administration/patients/add'

      ]


   ],

];
