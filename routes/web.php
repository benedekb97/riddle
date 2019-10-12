<?php


Route::get('','HomeController@index')->name('index');

// Admin routes
Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'], function(){
    Route::get('', 'AdminController@index')->name('index');
});


Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'users','as' => 'users.'], function(){
        Route::get('list','UsersController@list')->name('list');
        Route::get('riddles/{riddle?}/{option?}', 'UsersController@riddles')->name('riddles');
        Route::get('profile','UsersController@profile')->name('profile');

        Route::get('profile/edit','UsersController@edit')->name('profile.edit');

        Route::post('profile/save','UsersController@save')->name('profile.save');

        Route::get('creators','UsersController@creators')->name('creators')->middleware('moderator');
        Route::get('user/modify/{user}','UsersController@modify')->name('user.modify')->middleware('moderator');
    });

    Route::group(['prefix' => 'riddles', 'as' => 'riddles.'], function(){
        Route::get('new/{error?}','RiddleController@new')->name('new');
        Route::post('new','RiddleController@save')->name('save');

        Route::get('get/{riddle}', 'RiddleController@get')->name('get');

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

Route::get('auth/login','LoginController@authSchCallback')->name('auth_sch_callback');
Route::get('auth/redirect','LoginController@authSchRedirect')->name('login');

