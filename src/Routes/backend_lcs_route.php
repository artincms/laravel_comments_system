<?php
Route::group(['prefix' => config('laravel_comments_system.backend_lcs_route_prefix'), 'namespace' => 'ArtinCMS\LCS\Controllers', 'middleware' => config('laravel_comments_system.backend_lcs_middlewares')], function () {
    Route::get('indexCommentBackend', ['as' => 'LCS.indexCommentBackend', 'uses' => 'CommentController@indexCommentBackend']);
    Route::get('replyToComment/{id}', ['as' => 'LCS.replyToComment', 'uses' => 'CommentController@replyToComment']);
    Route::post('getCommentDataTable', ['as' => 'LCS.getCommentDataTable', 'uses' => 'CommentController@getCommentDataTable']);
    Route::post('showComment', ['as' => 'LCS.showComment', 'uses' => 'CommentController@showComment']);
    Route::post('storeReplyToComment', ['as' => 'LCS.storeReplyToComment', 'uses' => 'CommentController@storeReplyToComment']);
    Route::post('trashComment', ['as' => 'LCS.trashComment', 'uses' => 'CommentController@trashComment']);
    Route::post('approveComment', ['as' => 'LCS.approveComment', 'uses' => 'CommentController@approveComment']);

    Route::get('showSetting', ['as' => 'LCS.showSetting', 'uses' => 'CommentController@showSetting']);
    Route::post('createCommentItems', ['as' => 'LCS.createCommentItems', 'uses' => 'CommentController@createCommentItems']);
    Route::post('getCommentItemDatatable', ['as' => 'LCS.getCommentItemDatatable', 'uses' => 'CommentController@getCommentItemDatatable']);
    Route::post('getEditSettingsForm', ['as' => 'LCS.getEditSettingsForm', 'uses' => 'CommentController@getEditSettingsForm']);
    Route::post('editSetting', ['as' => 'LCS.editSetting', 'uses' => 'CommentController@editSetting']);
    Route::post('changeSettingStatus', ['as' => 'LCS.changeSettingStatus', 'uses' => 'CommentController@changeSettingStatus']);
    Route::post('trashSetting', ['as' => 'LCS.trashSetting', 'uses' => 'CommentController@trashSetting']);
    Route::post('getReplyToCommentForm', ['as' => 'LCS.getReplyToCommentForm', 'uses' => 'CommentController@getReplyToCommentForm']);
});