<?php
Route::group(['prefix' => config('laravel_comments_system.backend_lcs_route_prefix'), 'namespace' => 'ArtinCMS\LCS\Controllers', 'middleware' => config('laravel_comments_system.backend_lcs_middlewares')], function () {
    Route::get('indexCommentBackend', ['as' => 'LCS.comment', 'uses' => 'CommentController@indexCommentBackend']);
    Route::post('getCommentDataTable', ['as' => 'LCS.getCommentDataTable', 'uses' => 'CommentController@getCommentDataTable']);
    Route::post('showComment', ['as' => 'LCS.showComment', 'uses' => 'CommentController@showComment']);
});