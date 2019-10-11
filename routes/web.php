<?php


Route::get('','HomeController@index')->name('index');

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'users','as' => 'users.'], function(){
        Route::get('list','UsersController@list')->name('list');
        Route::get('riddles/{riddle?}/{option?}', 'UsersController@riddles')->name('riddles');
        Route::get('profile','UsersController@profile')->name('profile');

        Route::get('profile/edit','UsersController@edit')->name('profile.edit');

        Route::post('profile/save','UsersController@save')->name('profile.save');
    });

    Route::group(['prefix' => 'riddles', 'as' => 'riddles.'], function(){
        Route::get('new/{error?}','RiddleController@new')->name('new');
        Route::post('new','RiddleController@save')->name('save');

        Route::get('get/{riddle}', 'RiddleController@get')->name('get');

        Route::group(['middleware' => 'moderator'], function(){
            Route::get('unapproved','RiddleController@unapproved')->name('unapproved');
            Route::get('blocked','RiddleController@blocked')->name('blocked');
            Route::get('{riddle}/approve/{return?}', 'RiddleController@approve')->name('approve');
            Route::post('{riddle}/block/{return?}', 'RiddleController@block')->name('block');
        });

        Route::get('next','RiddleController@next')->name('next');

        Route::post('hintme/{riddle}','RiddleController@hint')->name('hintme');

        Route::get('{riddle}/hints/{hint}/delete', 'RiddleController@deleteHint')->name('hint.delete');
        Route::post('{riddle}/hints/add', 'RiddleController@addHint')->name('hint.add');

        Route::post('{riddle}/edit', 'RiddleController@edit')->name('edit');

        Route::get('current','HomeController@current')->name('current');

        Route::get('noneleft','HomeController@noneLeft')->name('noneleft');

        Route::get('all','RiddleController@all')->name('all');
    });

    Route::get('riddles/view/{riddle}/','HomeController@riddle')->name('riddle');

    Route::post('api/riddle/check/{riddle}', 'RiddleController@check')->name('api.riddle.check');

});

Route::get('auth/login','LoginController@authSchCallback')->name('auth_sch_callback');
Route::get('auth/redirect','LoginController@authSchRedirect')->name('login');

