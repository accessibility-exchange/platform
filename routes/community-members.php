<?php

use App\Http\Controllers\CommunityMemberController;

Route::multilingual('/members', [CommunityMemberController::class, 'index'])
    ->middleware(['auth'])
    ->name('community-members.index');

Route::multilingual('/members/create', [CommunityMemberController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\CommunityMember'])
    ->name('community-members.create');

Route::multilingual('/members/create', [CommunityMemberController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\CommunityMember'])
    ->name('community-members.store');

Route::multilingual('/members/{communityMember}', [CommunityMemberController::class, 'show'])
    ->middleware(['auth', 'can:view,communityMember'])
    ->name('community-members.show');

Route::multilingual('/members/{communityMember}/interests-and-goals', [CommunityMemberController::class, 'show'])
    ->middleware(['auth', 'can:view,communityMember'])
    ->name('community-members.show-interests-and-goals');

Route::multilingual('/members/{communityMember}/lived-experience', [CommunityMemberController::class, 'show'])
    ->middleware(['auth', 'can:viewPersonalDetails,communityMember'])
    ->name('community-members.show-lived-experience');

Route::multilingual('/members/{communityMember}/professional-experience', [CommunityMemberController::class, 'show'])
    ->middleware(['auth', 'can:view,communityMember'])
    ->name('community-members.show-professional-experience');

Route::multilingual('/members/{communityMember}/access-needs', [CommunityMemberController::class, 'show'])
    ->middleware(['auth', 'can:viewPersonalDetails,communityMember'])
    ->name('community-members.show-access-needs');

Route::multilingual('/members/{communityMember}/edit', [CommunityMemberController::class, 'edit'])
    ->middleware(['auth', 'can:update,communityMember'])
    ->name('community-members.edit');

Route::multilingual('/members/{communityMember}/edit', [CommunityMemberController::class, 'update'])
    ->middleware(['auth', 'can:update,communityMember'])
    ->method('put')
    ->name('community-members.update');

Route::multilingual('/members/{communityMember}/change-status', [CommunityMemberController::class, 'updatePublicationStatus'])
    ->middleware(['auth', 'can:update,communityMember'])
    ->method('put')
    ->name('community-members.update-publication-status');

Route::multilingual('/members/{communityMember}/express-interest', [CommunityMemberController::class, 'expressInterest'])
    ->method('post')
    ->middleware(['auth', 'can:update,communityMember'])
    ->name('community-members.express-interest');

Route::multilingual('/members/{communityMember}/remove-interest', [CommunityMemberController::class, 'removeInterest'])
    ->method('post')
    ->middleware(['auth', 'can:update,communityMember'])
    ->name('community-members.remove-interest');

Route::multilingual('/members/{communityMember}/delete', [CommunityMemberController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,communityMember'])
    ->method('delete')
    ->name('community-members.destroy');
