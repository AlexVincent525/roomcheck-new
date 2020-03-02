<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'test', 'namespace' => 'Test'], function () {
    Route::get('login-test-user', 'TestController@loginTestUser');
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::group(['prefix' => 'login', 'namespace' => 'Login'], function () {
        Route::get('/', 'IndexController@index')->name('login-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::post('login', 'AjaxController@login');
        });
    });
});

Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {
    Route::get('/', 'IndexController@index')->name('home-page');
});

Route::group(['prefix' => 'room-check', 'namespace' => 'RoomCheck'], function () {
    Route::get('/', 'IndexController@index')->name('room-check-page');
    Route::group(['prefix' => 'ajax'], function () {
        Route::get('level-one-check-set', 'AjaxController@levelOneCheckList');
        Route::get('level-two-check-set-list', 'AjaxController@levelTwoTaskSetList');
        Route::get('check-task-set', 'AjaxController@checkTaskList');
    });
});

Route::group(['prefix' => 'check-management', 'namespace' => 'CheckManagement'], function () {
    Route::group(['prefix' => 'create-check', 'namespace' => 'CreateCheck'], function () {
        Route::get('/', 'IndexController@index')->name('create-check-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('level-one-check-task-set-list', 'AjaxController@levelOneCheckList');
            Route::post('create-level-one-task-set', 'AjaxController@createLevelOneTaskSet');
        });
    });
    Route::group(['prefix' => 'distribute-check', 'namespace' => 'DistributeCheck'], function () {
        Route::get('/', 'IndexController@index')->name('distribute-check-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('level-one-check-task-set-list', 'AjaxController@levelOneCheckTaskSetList');
            Route::get('level-two-check-task-set-list', 'AjaxController@levelTwoTaskSetList');
            Route::get('user-list', 'AjaxController@userList');
            Route::post('create-check-task', 'AjaxController@createCheckTask');
        });
    });
});

Route::group(['prefix' => 'basic-information-management', 'namespace' => 'BasicInformationManagement'], function () {
    Route::group(['prefix' => 'room-management', 'namespace' => 'RoomManagement'], function () {
        Route::get('/', 'IndexController@index')->name('room-management-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('room-list', 'AjaxController@roomList');
            Route::get('building-list', 'AjaxController@buildingList');
            Route::post('add-room', 'AjaxController@addRoom');
            Route::post('change-room-status', 'AjaxController@changeRoomStatus');
            Route::post('import-room', 'AjaxController@importRoom');
        });
    });
    Route::group(['prefix' => 'building-management', 'namespace' => 'BuildingManagement'], function () {
        Route::get('/', 'IndexController@index')->name('building-management-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('building-list', 'AjaxController@buildingList');
            Route::post('change-building-status', 'AjaxController@changeBuildingStatus');
            Route::post('add-building', 'AjaxController@addBuilding');
        });
    });
    Route::group(['prefix' => 'item-management', 'namespace' => 'ItemManagement'], function () {
        Route::get('/', 'IndexController@index')->name('item-management-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('building-list', 'AjaxController@buildingList');
            Route::get('item-list', 'AjaxController@itemList');
            Route::post('edit-item', 'AjaxController@editItem');
        });
    });
});

Route::group(['prefix' => 'user-management', 'namespace' => 'UserManagement'], function () {
    Route::group(['prefix' => 'members-management', 'namespace' => 'MembersManagement'], function () {
        Route::get('/', 'IndexController@index')->name('members-management-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::post('add-member', 'AjaxController@addMember');
            Route::get('member-list', 'AjaxController@userList');
            Route::patch('edit-member-profile', 'AjaxController@editUserProfile');
            Route::post('active-member', 'AjaxController@changeMemberStatus');
            Route::post('import-member', 'AjaxController@importUser');
        });
    });
    Route::group(['prefix' => 'vice-leader-management', 'namespace' => 'ViceLeaderManagement'], function () {
        Route::get('/', 'IndexController@index')->name('vice-leader-management-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('vice-leader-list', 'AjaxController@ViceLeaderList');
            Route::post('add-vice-leader', 'AjaxController@addViceLeader');
            Route::post('active-vice-leader', 'AjaxController@changeMemberStatus');
        });
    });
    Route::group(['prefix' => 'change-profile', 'namespace' => 'ChangeProfile'], function () {
        Route::get('/', 'IndexController@index')->name('change-profile-page');
        Route::group(['prefix' => 'ajax'], function () {
            Route::post('change-name', 'AjaxController@changeName');
            Route::post('change-password', 'AjaxController@changePassword');
        });
    });
});

