<?php

return [

    /* Important Settings */
    'backend_lcs_middlewares'   => ['web'],
    'frontend_lcs_middlewares'  => ['web'],
    // you can change default route from sms-admin to anything you want
    'backend_lcs_route_prefix'  => 'LCS',
    'frontend_lcs_route_prefix' => 'LCS',
    // SMS.ir Api Key
    'api-key'                   => env('SMSIR-API-KEY', 'Your api key'),
    // ======================================================================
    //allow user to upload private file in filemanager
    'autoPublish'               => true,
    'guestCanComments'          => false,
    'loginUrl'                  => 'http://127.0.0.1:8000/login',
    'registerUrl'               => 'http://127.0.0.1:8000/register',
    'user_model'                 => 'App\User',
    'show_comment_item'         => true,
    'Trait'                     => [
        'Path'   => 'App\Traits\LaravelCommentSystem',
        'Name'   => 'LaravelCommentSystem',
        'Method' => 'setDataTableColumn',
    ]


];