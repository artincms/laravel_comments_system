<?php
Route::group(['prefix' => config('laravel_file_manager.private_route_prefix'), 'namespace' => 'ArtinCMS\LFM\Controllers', 'middleware' => config('laravel_file_manager.private_middlewares')], function () {
    //Route::get('ShowCategories/{insert?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories', 'uses' => 'ManagerController@showCategories']);
});