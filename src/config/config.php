<?php

return [
    'path' => 'admin',

    'default_route' => 'DashboardController@index',

    'auth' => [
        'login' => 'admin',
        'email' => 'admin@sitename.com',
        'password' => 'admin'
    ]
];