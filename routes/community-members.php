<?php

use App\Http\Controllers\CommunityMemberController;

Route::controller(CommunityMemberController::class)->group(function () {
    Route::multilingual('/members', 'index')
        ->middleware(['auth'])
        ->name('community-members.index');

    Route::multilingual('/role-selection', [CommunityMemberController::class, 'showRoleSelection'])
        ->middleware(['auth'])
        ->name('community-members.show-role-selection');

    Route::multilingual('/role/save', [CommunityMemberController::class, 'saveRole'])
        ->method('put')
        ->middleware(['auth'])
        ->name('community-members.save-role');

    Route::multilingual('/members/{communityMember}', 'show')
        ->middleware(['auth', 'can:view,communityMember'])
        ->name('community-members.show');

    Route::multilingual('/members/{communityMember}/interests', 'show')
        ->middleware(['auth', 'can:view,communityMember'])
        ->name('community-members.show-interests');

    Route::multilingual('/members/{communityMember}/experiences', 'show')
        ->middleware(['auth', 'can:view,communityMember'])
        ->name('community-members.show-experiences');

    Route::multilingual('/members/{communityMember}/access-needs', 'show')
        ->middleware(['auth', 'can:view,communityMember'])
        ->name('community-members.show-access-needs');

    Route::multilingual('/members/{communityMember}/edit', 'edit')
        ->middleware(['auth', 'can:update,communityMember'])
        ->name('community-members.edit');

    Route::multilingual('/members/{communityMember}/edit', 'update')
        ->middleware(['auth', 'can:update,communityMember'])
        ->method('put')
        ->name('community-members.update');

    Route::multilingual('/members/{communityMember}/edit-interests', 'updateInterests')
        ->middleware(['auth', 'can:update,communityMember'])
        ->method('put')
        ->name('community-members.update-interests');

    Route::multilingual('/members/{communityMember}/edit-experiences', 'updateExperiences')
        ->middleware(['auth', 'can:update,communityMember'])
        ->method('put')
        ->name('community-members.update-experiences');

    Route::multilingual('/members/{communityMember}/edit-communication-and-meeting-preferences', 'updateCommunicationAndMeetingPreferences')
        ->middleware(['auth', 'can:update,communityMember'])
        ->method('put')
        ->name('community-members.update-communication-and-meeting-preferences');

    Route::multilingual('/members/{communityMember}/change-status', 'updatePublicationStatus')
        ->middleware(['auth', 'can:update,communityMember'])
        ->method('put')
        ->name('community-members.update-publication-status');

    Route::multilingual('/members/{communityMember}/express-interest', 'expressInterest')
        ->method('post')
        ->middleware(['auth', 'can:update,communityMember'])
        ->name('community-members.express-interest');

    Route::multilingual('/members/{communityMember}/remove-interest', 'removeInterest')
        ->method('post')
        ->middleware(['auth', 'can:update,communityMember'])
        ->name('community-members.remove-interest');

    Route::multilingual('/members/{communityMember}/delete', 'destroy')
        ->middleware(['auth', 'can:delete,communityMember'])
        ->method('delete')
        ->name('community-members.destroy');
});
