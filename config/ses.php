<?php

return [

    'ocuhub-idp' => env('SES_IDP_URL'),
    'aes_key' => 'OcuHub123Secret#',
    'ses' => [
        'email_id' => 'support@ocuhub.com',
        'email_name' => 'OcuHub',
        'phone' => '844-605-8243',
        'contact_form' => 'www.ocuhub.com/contact',
        'application_error' => 'applicationerror@ocuhub.com',
        'ses' => [
            'email' => [
                'id' => 'Admin2@direct.ocuhub.com',
                'display_name' => 'Support OcuHub',
            ],
        ],
    ],

];
