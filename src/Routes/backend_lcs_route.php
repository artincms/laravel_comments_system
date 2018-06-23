<?php
Route::group(['prefix' => config('laravel_comments_system.backend_lcs_route_prefix'), 'namespace' => 'ArtinCMS\LCS\Controllers', 'middleware' => config('laravel_comments_system.backend_lcs_middlewares')], function () {
    Route::get('indexCommentBackend', ['as' => 'LCS.indexCommentBackend', 'uses' => 'CommentController@indexCommentBackend']);
    Route::get('replyToComment/{id}', ['as' => 'LCS.replyToComment', 'uses' => 'CommentController@replyToComment']);
    Route::post('getCommentDataTable', ['as' => 'LCS.getCommentDataTable', 'uses' => 'CommentController@getCommentDataTable']);
    Route::post('showComment', ['as' => 'LCS.showComment', 'uses' => 'CommentController@showComment']);
    Route::post('storeReplyToComment', ['as' => 'LCS.storeReplyToComment', 'uses' => 'CommentController@storeReplyToComment']);
    Route::post('trashComment', ['as' => 'LCS.trashComment', 'uses' => 'CommentController@trashComment']);
    Route::post('approveComment', ['as' => 'LCS.approveComment', 'uses' => 'CommentController@approveComment']);

    //decode id
    Route::bind('id',function ($v,$r){
        return LCS_GetDecodeId($v,$r);
    });
});