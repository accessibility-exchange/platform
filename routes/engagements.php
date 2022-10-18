<?php

use App\Http\Controllers\EngagementController;
use App\Http\Livewire\AddEngagementConnector;
use App\Http\Livewire\ManageEngagementConnector;

Route::controller(EngagementController::class)
    ->name('engagements.')
    ->group(function () {
        Route::multilingual('/projects/{project}/engagements/create/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('show-language-selection');

        Route::multilingual('/projects/{project}/engagements/create/store-languages', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('store-languages');

        Route::multilingual('/projects/{project}/engagements/create', 'create')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('create');

        Route::multilingual('/projects/{project}/engagements/create', 'store')
            ->method('post')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('store');
    });

Route::controller(EngagementController::class)
    ->prefix('engagements')
    ->name('engagements.')
    ->group(function () {
        Route::multilingual('/{engagement}', 'show')
            ->middleware(['auth', 'verified', 'can:view,engagement'])
            ->name('show');

        Route::multilingual('/{engagement}/format/select', 'showFormatSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-format-selection');

        Route::multilingual('/{engagement}/format/store', 'storeFormat')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('store-format');

        Route::multilingual('/{engagement}/recruitment/select', 'showRecruitmentSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-recruitment-selection');

        Route::multilingual('/{engagement}/recruitment/store', 'storeRecruitment')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('store-recruitment');

        Route::multilingual('/{engagement}/criteria/select', 'showCriteriaSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-criteria-selection');

        Route::multilingual('/{engagement}/edit', 'edit')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit');

        Route::multilingual('/{engagement}/update', 'update')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{engagement}/languages/edit', 'editLanguages')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit-languages');

        Route::multilingual('/{engagement}/languages/update', 'updateLanguages')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update-languages');

        Route::multilingual('/{engagement}/criteria/edit', 'editCriteria')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit-criteria');

        Route::multilingual('/{engagement}/criteria/update', 'updateCriteria')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update-criteria');

        Route::multilingual('/{engagement}/manage', 'manage')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('manage');

        Route::multilingual('/{engagement}/manage/organization', 'manageOrganization')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('manage-organization');

        Route::multilingual('/{engagement}/manage/organization/add', 'addOrganization')
            ->method('post')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('add-organization');

        Route::multilingual('/{engagement}/manage/organization/remove', 'removeOrganization')
            ->method('post')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('remove-organization');

        Route::multilingual('/{engagement}/manage/participants', 'manageParticipants')
            ->middleware(['auth', 'can:viewParticipants,engagement'])
            ->name('manage-participants');

        Route::multilingual('/{engagement}/manage/access-needs', 'manageAccessNeeds')
            ->middleware(['auth', 'can:viewParticipants,engagement'])
            ->name('manage-access-needs');

        Route::multilingual('/{engagement}/manage/add-participant', 'addParticipant')
            ->middleware(['auth', 'can:addParticipants,engagement'])
            ->name('add-participant');

        Route::multilingual('/{engagement}/manage/invite-participant', 'inviteParticipant')
            ->method('post')
            ->middleware(['auth', 'can:addParticipants,engagement'])
            ->name('invite-participant');

        Route::multilingual('/{engagement}/sign-up', 'signUp')
            ->middleware(['auth', 'can:join,engagement'])
            ->name('sign-up');

        Route::multilingual('/{engagement}/join', 'join')
            ->method('post')
            ->middleware(['auth', 'can:join,engagement'])
            ->name('join');

        Route::multilingual('/{engagement}/confirm-access-needs', 'confirmAccessNeeds')
            ->middleware(['auth', 'can:participate,engagement'])
            ->name('confirm-access-needs');

        Route::multilingual('/{engagement}/store-access-needs-permissions', 'storeAccessNeedsPermissions')
            ->method('post')
            ->middleware(['auth', 'can:participate,engagement'])
            ->name('store-access-needs-permissions');

        Route::multilingual('/{engagement}/leave', 'confirmLeave')
            ->middleware(['auth', 'can:participate,engagement'])
            ->name('confirm-leave');

        Route::multilingual('/{engagement}/exit', 'leave')
            ->method('post')
            ->middleware(['auth', 'can:leave,engagement'])
            ->name('leave');
    });

Route::multilingual('/engagements/{engagement}/connector/manage', [ManageEngagementConnector::class, '__invoke'])
    ->middleware(['auth', 'can:update,engagement'])
    ->name('engagements.manage-connector');

Route::multilingual('/engagements/{engagement}/connector/add', [AddEngagementConnector::class, '__invoke'])
    ->middleware(['auth', 'can:addConnector,engagement'])
    ->name('engagements.add-connector');
