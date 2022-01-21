<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return inertia('Dashboard');
    })->name('home');

    // user
    Route::get('/user/profile', 'UserController@showProfile')->name('user.profile.show');
    Route::delete('/user/other-browser-sessions', 'UserController@destroySession')->name('user.other-browser-sessions.destroy');
    Route::delete('/user/profile-photo', 'UserController@destroyProfilePhoto')->name('user.current-user-photo.destroy');
    Route::delete('/user', 'UserController@destroy')->name('user.destroy');

    // api tokens
    Route::get('/user/api-tokens', 'ApiTokenController@index')->name('api-tokens.index');
    Route::post('/user/api-tokens', 'ApiTokenController@store')->name('api-tokens.store');
    Route::put('/user/api-tokens/{token}', 'ApiTokenController@update')->name('api-tokens.update');
    Route::delete('/user/api-tokens/{token}', 'ApiTokenController@destroy')->name('api-tokens.destroy');

    // teams
    Route::get('/teams/create', 'TeamController@create')->name('teams.create');
    Route::post('/teams', 'TeamController@store')->name('teams.store');
    Route::get('/teams/{team}', 'TeamController@show')->name('teams.show');
    Route::put('/teams/{team}', 'TeamController@update')->name('teams.update');
    Route::delete('/teams/{team}', 'TeamController@destroy')->name('teams.destroy');
    Route::put('/current-team', 'TeamController@updateCurrentTeam')->name('current-team.update');
    Route::post('/teams/{team}/members', 'TeamMemberController@store')->name('team-members.store');
    Route::put('/teams/{team}/members/{user}', 'TeamMemberController@update')->name('team-members.update');
    Route::delete('/teams/{team}/members/{user}', 'TeamMemberController@destroy')->name('team-members.destroy');
    Route::get('/team-invitations/{invitation}', 'TeamMemberController@acceptInvitation')->middleware(['signed'])->name('team-invitations.accept');
    Route::delete('/team-invitations/{invitation}', 'TeamMemberController@destroyInvitation')->name('team-invitations.destroy');
});

Route::get('/privacy', function () {
    return view('home.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('home.terms');
})->name('terms');
