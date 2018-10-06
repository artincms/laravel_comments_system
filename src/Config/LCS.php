<?php

return [

    /* Important Settings */
    'backend_lcs_middlewares'   => explode(',', env('LCS_BACKEND_MIDDLEWARES', 'web')),
    'frontend_lcs_middlewares'  => explode(',', env('LCS_FRONTEND_MIDDLEWARES', 'web')),
    // you can change default route from sms-admin to anything you want
    'backend_lcs_route_prefix'  => env('LCS_BACKEND_ROUTE_PERFIX', 'LCS'),
    'frontend_lcs_route_prefix' => env('LCS_FRONTEND_ROUTE_PERFIX', 'LCS'),
    // ======================================================================
    //allow user to upload private file in filemanager
    'auto_publish'              => env('LCS_AUTO_PUBLISH', true),
    'guest_can_comments'        => env('LCS_AUTO_PUBLISH', false),
    'login_url'                 => env('LCS_LOGIN_URL', 'http://127.0.0.1:8000/login'),
    'register_url'              => env('LCS_REGISTER_URL', 'http://127.0.0.1:8000/register'),
    'user_model'                => env('LCS_USER_MODEL', 'App\User'),
    'user_name_column'                => env('LCS_USERNAME_COLUMN', 'name'),
    'user_email_column'                => env('LCS_EMAIL_COLUMN', 'email'),
    'show_comment_item'         => env('LCS_SHOW_COMMENT_ITEM', true),
    'user_data'                 => env('LCS_USER_DATA', 'LCS_GetUserInformation'),
    'trait'                     => [
        'Path'   => 'App\Traits\LaravelCommentSystem',
        'Name'   => 'LaravelCommentSystem',
        'Method' => 'setDataTableColumn',
    ]


];