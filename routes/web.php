<?php


Route::get('','HomeController@index')->name('index');

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'users','as' => 'users.'], function(){
        Route::get('list','UsersController@list')->name('list');
        Route::get('riddles', 'UsersController@riddles')->name('riddles');
        Route::get('profile','UsersController@profile')->name('profile');
    });

    Route::group(['prefix' => 'riddles', 'as' => 'riddles.'], function(){
        Route::get('new/{error?}','RiddleController@new')->name('new');
        Route::post('new','RiddleController@save')->name('save');

        Route::get('get/{riddle}', 'RiddleController@get')->name('get');

        Route::group(['middleware' => 'moderator'], function(){
            Route::get('unapproved','RiddleController@unapproved')->name('unapproved');
        });

        Route::get('fresh', 'RiddkeController@fresh')->name('fresh');

        Route::get('next','RiddleController@next')->name('next');
    });

    Route::get('riddle/{riddle}','HomeController@riddle')->name('riddle');

    Route::post('api/riddle/check/{riddle}', 'RiddleController@check')->name('api.riddle.check');

});

Route::get('auth/login','LoginController@authSchCallback')->name('auth_sch_callback');
Route::get('auth/redirect','LoginController@authSchRedirect')->name('login');

