<?php


Route::post('login/check/user','LoginController@check')->name('login.check');
Route::post('register','LoginController@register')->name('register');

Route::get('logout', 'LoginController@logout')->name('logout');

// Admin routes
Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'], function(){
    Route::get('', 'AdminController@index')->name('index');

    Route::group(['prefix'=>'static_messages'], function(){
        Route::get('','AdminController@staticMessages')->name('static_messages');
        Route::post('edit/{message}','AdminController@editStaticMessage')->name('static_messages.edit');
        Route::post('delete/{message}','AdminController@deleteStaticMessage')->name('static_messages.delete');
        Route::post('new','AdminController@newStaticMEssage')->name('static_messages.new');
    });

    Route::group(['prefix' => 'moderators','as'=>'moderators.'], function(){
        Route::get('','AdminController@moderators')->name('index');
        Route::post('add','AdminController@addModerator')->name('add');
        Route::post('search','AdminController@search')->name('search');
        Route::get('delelte/{user}','AdminController@deleteModerator')->name('delete');
    });

    Route::group(['prefix' => 'logs', 'as' => 'logs.'], function(){
        Route::get('','AdminController@logs')->name('index');
        Route::get('data','AdminController@logData')->name('data');
    });

    Route::group(['prefix' => 'functions', 'as' => 'functions.'], function(){
        Route::get('', 'AdminController@functions')->name('index');
        Route::get('reset_current_riddles','AdminController@resetCurrentRiddles')->name('reset_current_riddles');
        Route::get('set_points','AdminController@setPoints')->name('set_points');

        Route::get('lockdown/enable','AdminController@enableLockdown')->name('lockdown.enable');
        Route::get('lockdown/disable','AdminController@disableLockdown')->name('lockdown.disable');
    });

    Route::group(['prefix' => 'users', 'as' => 'users.'], function(){
        Route::get('', 'AdminController@users')->name('index');

        Route::get('data','AdminController@userData')->name('data');

//        Route::get('delete/{user}', 'AdminController@deleteUser')->name('delete');

        Route::get('block/{user}', 'AdminController@blockUser')->name('block');
        Route::get('unblock/{user}', 'AdminController@unblockUser')->name('unblock');
    });

    Route::group(['prefix' => 'api', 'as' => 'api.'], function(){
        Route::get('','AdminController@api')->name('index');

        Route::get('generate_keys', 'AdminController@newApiTokens')->name('generate_keys');
    });

    Route::group(['prefix' => 'riddles', 'as' => 'riddles.'], function(){
        Route::get('','AdminController@riddles')->name('index');
        Route::get('data','AdminController@riddleData')->name('data');
    });

    Route::get('settings','AdminController@settings')->name('settings');

    Route::get('profile','AdminControler@profile')->name('profile');
});

Route::get('login/{error?}','HomeController@login')->name('login');

Route::group(['middleware' => 'auth'], function(){
    Route::group(['middleware' => 'settings'], function(){

        Route::get('api/description','ApiController@description')->name('description');

        Route::get('','HomeController@index')->name('index');


        Route::group(['prefix' => 'users','as' => 'users.'], function(){
            Route::get('list','UsersController@list')->name('list');
            Route::get('riddles/{riddle?}/{option?}', 'UsersController@riddles')->name('riddles');
            Route::get('profile','UsersController@profile')->name('profile');

            Route::get('profile/edit/{error?}','UsersController@edit')->name('profile.edit');

            Route::post('profile/save','UsersController@save')->name('profile.save');

            Route::get('creators','UsersController@creators')->name('creators')->middleware('moderator');
            Route::get('user/modify/{user}','UsersController@modify')->name('user.modify')->middleware('moderator');

            Route::get('helps','UsersController@helps')->name('helps');
            Route::post('help.send','RiddleController@sendHelp')->name('help.send');
        });

        Route::group(['prefix' => 'riddles', 'as' => 'riddles.'], function(){
            Route::get('new/{error?}','RiddleController@new')->name('new');
            Route::post('new','RiddleController@save')->name('save');

            Route::get('get/{riddle}', 'RiddleController@get')->name('get');

            Route::post('help','RiddleController@help')->name('help');

            Route::post('duplicate','RiddleController@duplicate')->name('duplicate');

            Route::group(['middleware' => 'moderator'], function(){
                Route::get('unapproved','RiddleController@unapproved')->name('unapproved');
                Route::get('blocked','RiddleController@blocked')->name('blocked');
                Route::get('{riddle}/approve/{return?}', 'RiddleController@approve')->name('approve');
                Route::post('{riddle}/block/{return?}', 'RiddleController@block')->name('block');

                Route::get('sequence', 'RiddleController@sequence')->name('sequence');
                Route::get('sequence/{riddle}/add','RiddleController@addToSequence')->name('sequence.add');
                Route::get('sequence/{riddle}/down', 'RiddleController@sequenceDown')->name('sequence.down');
                Route::get('sequence/{riddle}/up', 'RiddleController@sequenceUp')->name('sequence.up');

                Route::get('duplicates','RiddleController@duplicates')->name('duplicates');

                Route::get('duplicates/delete/report/{duplicate}','RiddleController@deleteReport')->name('duplicates.delete.report');
                Route::get('delete/{riddle}','RiddleController@deleteRiddle')->name('delete');
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



});

Route::get('auth/login','LoginController@authSchCallback')->name('auth_sch_callback');
Route::get('auth/redirect','LoginController@authSchRedirect')->name('auth_sch_login');

Route::group(['prefix' => 'api','as' => 'api.'], function(){ // needs to be auth:api when deployed

    Route::post('user','ApiController@user')->name('user');
    Route::post('riddle','ApiController@riddle')->name('riddle');
    Route::post('next','ApiController@nextRiddle')->name('next');
    Route::post('check','ApiController@checkRiddle')->name('check');
    Route::post('home','ApiController@home')->name('home');
    Route::post('previous', 'ApiController@previous')->name('previous');
    Route::post('hasHintsLeft','ApiController@hasHintsLeft')->name('hasHintsLeft');
    Route::post('scores','ApiController@scores')->name('scores');
    Route::post('nextHint','ApiController@nextHint')->name('nextHint');

    Route::get('get/riddle/{riddle}/api_key/{api_key}', 'ApiController@getRiddle')->name('get.riddle');

    Route::post('login','LoginController@apiLogin')->name('login');
    Route::post('register', 'LoginController@apiRegister')->name('register');
});
