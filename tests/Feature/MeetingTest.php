<?php

use App\Enums\MeetingType;
use App\Enums\UserContext;
use App\Http\Requests\MeetingRequest;
use App\Models\Engagement;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Database\Seeders\IdentitySeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $otherUser = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
    ]);
});

test('meetings can be created', function () {
    seed(IdentitySeeder::class);

    $user = User::where('context', 'regulated-organization')->first();
    $otherUser = User::where('context', 'individual')->first();
    $regulatedOrganization = $user->regulated_organization;
    $project = $regulatedOrganization->projects->first();
    $engagement = $project->allEngagements->first();

    expect($engagement->meeting_dates)->toBeNull();
    expect($engagement->display_meeting_types)->toBeEmpty();

    actingAs($otherUser)->get(localized_route('meetings.create', $engagement))
        ->assertForbidden();

    actingAs($user)->get(localized_route('meetings.create', $engagement))
        ->assertOk();

    actingAs($user)->post(localized_route('meetings.store', $engagement), [
        'title' => ['en' => 'Meeting 1'],
        'date' => '2022-11-15',
        'start_time' => '9:00',
        'end_time' => '17:00',
        'timezone' => 'America/Edmonton',
        'meeting_types' => ['in_person', 'web_conference', 'phone'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M4W 1E6',
        'meeting_software' => 'WebMeetingApp',
        'meeting_url' => 'https://example.com/meet',
        'meeting_phone' => '6476231847',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertSee('Meeting 1')
        ->assertSee('Tuesday, November 15, 2022 9:00 AM');

    expect($engagement->fresh()->meeting_dates)->toEqual('November 15, 2022');
});

test('meetings can be edited', function () {
    $user = User::where('context', 'regulated-organization')->first();
    $otherUser = User::where('context', 'individual')->first();
    $regulatedOrganization = $user->regulated_organization;
    $project = $regulatedOrganization->projects->first();
    $engagement = $project->allEngagements->first();
    $meeting = Meeting::factory()->create([
        'engagement_id' => $engagement->id,
        'title' => ['en' => 'Meeting 1'],
        'date' => '2022-11-15',
        'start_time' => '9:00',
        'end_time' => '17:00',
        'timezone' => 'America/Edmonton',
        'meeting_types' => ['in_person'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M4W 1E6',
    ]);
    $meeting2 = Meeting::factory()->create([
        'engagement_id' => $engagement->id,
        'title' => ['en' => 'Meeting 2'],
        'date' => '2022-12-15',
        'start_time' => '9:00',
        'end_time' => '17:00',
        'timezone' => 'America/Edmonton',
        'meeting_types' => ['web_conference'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M4W 1E6',
    ]);

    actingAs($otherUser)->get(localized_route('meetings.edit', ['meeting' => $meeting, 'engagement' => $engagement]))
        ->assertForbidden();

    actingAs($user)->get(localized_route('meetings.edit', ['meeting' => $meeting, 'engagement' => $engagement]))
        ->assertOk();

    $meeting = $meeting->fresh();
    expect($engagement->meeting_dates)->toEqual('November 15–December 15, 2022');

    actingAs($user)->put(localized_route('meetings.update', ['meeting' => $meeting, 'engagement' => $engagement]), [
        'title' => ['en' => 'Meeting 1'],
        'date' => '2022-12-06',
        'start_time' => '9:00',
        'end_time' => '17:00',
        'timezone' => 'America/Edmonton',
        'meeting_types' => ['in_person', 'web_conference', 'phone'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M4W 1E6',
        'meeting_software' => 'WebMeetingApp',
        'meeting_url' => 'https://example.com/meet',
        'meeting_phone' => '6476231847',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $meeting = $meeting->fresh();
    $engagement = $engagement->fresh();
    expect($meeting->meeting_types)->toHaveCount(3);
    expect($engagement->meeting_dates)->toEqual('December 6–15, 2022');
    expect($engagement->meetingTypesIncludes('in_person'))->toBeTrue();
    expect($engagement->display_meeting_types)->toContain('In person');
    expect($engagement->display_meeting_types)->toContain('Virtual – web conference');
});

test('Meeting request validation errors', function ($state, array $errors, $modifiers = []) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->has(Project::factory()->has(Engagement::factory()->has(Meeting::factory())))
        ->create();

    $engagement = $regulatedOrganization->projects->first()->engagements->first();
    $meeting = $engagement->meetings->first();

    $requestFactory = MeetingRequest::factory();

    $meetingTypeTransformer = match ($modifiers['meetingType'] ?? '') {
        MeetingType::InPerson->value => 'inPerson',
        MeetingType::Phone->value => 'phone',
        MeetingType::WebConference->value => 'webConference',
        default => null
    };

    if ($meetingTypeTransformer) {
        $requestFactory = $requestFactory->$meetingTypeTransformer();
    }

    $data = $requestFactory->without($modifiers['without'] ?? [])->create($state);

    // create meeting
    actingAs($user)->post(localized_route('meetings.store', $engagement), $data)
        ->assertSessionHasErrors($errors);

    // update existing meeting
    actingAs($user)->post(localized_route('meetings.update', ['meeting' => $meeting, 'engagement' => $engagement]), $data)
        ->assertSessionHasErrors($errors);
})->with('meetingRequestValidationErrors');

test('meetings can be deleted', function () {
    seed(IdentitySeeder::class);

    $user = User::where('context', 'regulated-organization')->first();
    $otherUser = User::where('context', 'individual')->first();
    $regulatedOrganization = $user->regulated_organization;
    $project = $regulatedOrganization->projects->first();
    $engagement = $project->allEngagements->first();
    $meeting = Meeting::factory()->create([
        'engagement_id' => $engagement->id,
        'title' => ['en' => 'Meeting 1'],
        'date' => '2022-11-15',
        'start_time' => '9:00',
        'end_time' => '17:00',
        'timezone' => 'America/Edmonton',
        'meeting_types' => ['in_person'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M4W 1E6',
    ]);

    actingAs($otherUser)->delete(localized_route('meetings.destroy', ['meeting' => $meeting, 'engagement' => $engagement]))
        ->assertForbidden();

    actingAs($user)->delete(localized_route('meetings.destroy', ['meeting' => $meeting, 'engagement' => $engagement]))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertSee('No meetings found.');
});
