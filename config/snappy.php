<?php

return array(

    'pdf' => array(
        'enabled' => true,
        'binary'  => base_path(env('PDF_Library')),
        'timeout' => false,
        'options' => array(
            'zoom' => 0.74125,
            'margin-bottom' => 0,
            'margin-left' => 0,
            'margin-right' => 0,
            'margin-top' => 0,
        )
    )
);
