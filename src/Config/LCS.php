<?php

return [

    /* Important Settings */
    'backend_lcs_middlewares'  => explode(',', env('BACKEND_LCS_MIDDLEWARES', 'web')),
    'frontend_lcs_middlewares' => explode(',', env('FRONTEND_LCS_MIDDLEWARES', 'web')),
    // you can change default route from sms-admin to anything you want
    'backend_lcs_route_prefix' => env('BACKEND_LCS_ROUTE_PERFIX', 'LCS'),
    'frontend_lcs_route_prefix' => env('FRONTEND_LCS_ROUTE_PERFIX', 'LCS'),
    // ======================================================================
    //allow user to upload private file in filemanager
    'auto_publish'             => env('LCS_AUTO_PUBLISH', true),
    'guest_can_comments'       => env('LCS_AUTO_PUBLISH', false),
    'login_url'                => env('LCS_LOGIN_URL', 'http://127.0.0.1:8000/login'),
    'register_url'             => env('LCS_REGISTER_URL', 'http://127.0.0.1:8000/register'),
    'user_model'               => env('LCS_USER_MODEL', 'App\User'),
    'show_comment_item'               => env('LCS_SHOW_COMMENT_ITEM', 'App\User'),
    'user_data'               => env('USER_DATA', 'LCS_GetUserInformation'),
    'trait'                    => [
        'Path'   => 'App\Traits\LaravelCommentSystem',
        'Name'   => 'LaravelCommentSystem',
        'Method' => 'setDataTableColumn',
    ]


];