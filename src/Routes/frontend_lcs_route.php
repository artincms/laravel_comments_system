<?php
Route::group(['prefix' => config('laravel_comments_system.frontend_lcs_route_prefix'), 'namespace' => 'ArtinCMS\LCS\Controllers', 'middleware' => config('laravel_comments_system.frontend_lcs_middlewares')], function () {
    Route::get('LeaveComments', ['as' => 'LCS.LeaveComments', 'uses' => 'CommentController@index']);
    Route::get('showCommentFronted/{id}/{target_id}', ['as' => 'LCS.showCommentFronted', 'uses' => 'CommentController@showCommentFronted']);
    Route::post('getData', ['as' => 'LCS.Getdata', 'uses' => 'CommentController@getdata']);
    Route::post('saveComment', ['as' => 'LCS.saveComment', 'uses' => 'CommentController@saveComment']);
});