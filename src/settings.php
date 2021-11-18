<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // jwt settings
        "jwt" => [
            'secret' => 'secret_key_to-ALEXIS-practice_api'
        ]
    ],
];
