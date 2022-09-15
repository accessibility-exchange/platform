<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', locale());
Route::multilingual('/', function () {
    return view('welcome');
})->name('welcome');

Route::prefix('about')
    ->name('about.')
    ->group(function () {
        Route::multilingual('/terms-of-service', function () {
            return view('about.terms-of-service');
        })->name('terms-of-service');

        Route::multilingual('/privacy-policy', function () {
            return view('about.privacy-policy');
        })->name('privacy-policy');

        Route::multilingual('/for-individuals', function () {
            return view('about.for-individuals');
        })->name('for-individuals');

        Route::multilingual('/for-individuals/consultation-participants', function () {
            return view('about.roles.consultation-participants');
        })->name('individual-consultation-participants');

        Route::multilingual('/for-individuals/consultation-participants/what-we-ask-for', function () {
            return view('about.roles.consultation-participant-details');
        })->name('individual-consultation-participants-what-we-ask-for');

        Route::multilingual('/for-individuals/accessibility-consultants', function () {
            return view('about.roles.accessibility-consultants');
        })->name('individual-accessibility-consultants');

        Route::multilingual('/for-individuals/accessibility-consultants/what-we-ask-for', function () {
            return view('about.roles.accessibility-consultant-details');
        })->name('individual-accessibility-consultants-what-we-ask-for');

        Route::multilingual('/for-individuals/community-connectors', function () {
            return view('about.roles.community-connectors');
        })->name('individual-community-connectors');

        Route::multilingual('/for-individuals/community-connectors/what-we-ask-for', function () {
            return view('about.roles.community-connector-details');
        })->name('individual-community-connectors-what-we-ask-for');

        Route::multilingual('/for-community-organizations', function () {
            return view('about.for-community-organizations');
        })->name('for-community-organizations');

        Route::multilingual('/for-community-organizations/consultation-participants', function () {
            return view('about.roles.consultation-participants');
        })->name('organization-consultation-participants');

        Route::multilingual('/for-community-organizations/accessibility-consultants', function () {
            return view('about.roles.accessibility-consultants');
        })->name('organization-accessibility-consultants');

        Route::multilingual('/for-community-organizations/community-connectors', function () {
            return view('about.roles.community-connectors');
        })->name('organization-community-connectors');

        Route::multilingual('/for-community-organizations/get-input', function () {
            return view('about.roles.get-input-for-projects');
        })->name('organization-get-input');

        Route::multilingual('/for-regulated-organizations', function () {
            return view('about.for-regulated-organizations');
        })->name('for-regulated-organizations');

        Route::multilingual('/for-regulated-organizations/get-input', function () {
            return view('about.roles.get-input-for-projects');
        })->name('regulated-organization-get-input');

        Route::multilingual('/pricing', function () {
            return view('about.pricing');
        })->name('pricing');
    });

Route::multilingual('/introduction', [UserController::class, 'showIntroduction'])
    ->middleware(['auth'])
    ->name('users.show-introduction');

Route::multilingual('/introduction/update', [UserController::class, 'updateIntroductionStatus'])
    ->method('put')
    ->middleware(['auth'])
    ->name('users.update-introduction-status');

Route::multilingual('/dashboard', [UserController::class, 'dashboard'])
    ->middleware(['auth', 'onboard'])
    ->name('dashboard');

Route::multilingual('/people-and-organizations', function () {
    return view('people-and-organizations');
})->middleware([
    'auth',
    'verified',
    'can:viewAny,App\Models\Individual',
    'can:viewAny,App\Models\Organization',
    'can:viewAny,App\Models\RegulatedOrganization',
])->name('people-and-organizations');

Route::multilingual('/people-and-organizations/consultants', function () {
    return 'The Accessibility Consultants page is not yet available.';
})->middleware([
    'auth',
    'verified',
    'can:viewAny,App\Models\Individual',
    'can:viewAny,App\Models\Organization',
])->name('people-and-organizations.consultants');

Route::multilingual('/people-and-organizations/connectors', function () {
    return 'The Community Consultants page is not yet available.';
})->middleware([
    'auth',
    'verified',
    'can:viewAny,App\Models\Individual',
    'can:viewAny,App\Models\Organization',
])->name('people-and-organizations.connectors');

Route::multilingual('/account/delete', [UserController::class, 'destroy'])
    ->method('delete')
    ->middleware(['auth'])
    ->name('users.destroy');

require __DIR__.'/admin.php';
require __DIR__.'/identifiers.php';
require __DIR__.'/block-list.php';
require __DIR__.'/individuals.php';
require __DIR__.'/defined-terms.php';
require __DIR__.'/organizations.php';
require __DIR__.'/regulated-organizations.php';
require __DIR__.'/projects.php';
require __DIR__.'/notification-list.php';
require __DIR__.'/engagements.php';
require __DIR__.'/resources.php';
require __DIR__.'/resource-collections.php';
require __DIR__.'/translations.php';
require __DIR__.'/memberships.php';
require __DIR__.'/invitations.php';
require __DIR__.'/fortify.php';
require __DIR__.'/settings.php';
