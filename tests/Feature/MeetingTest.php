<?php

use App\Models\Engagement;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Database\Seeders\DisabilityTypeSeeder;

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
    $this->seed(DisabilityTypeSeeder::class);

    $user = User::where('context', 'regulated-organization')->first();
    $otherUser = User::where('context', 'individual')->first();
    $regulatedOrganization = $user->regulated_organization;
    $project = $regulatedOrganization->projects->first();
    $engagement = $project->engagements->first();

    expect($engagement->meeting_dates)->toBeNull();

    $response = $this->actingAs($otherUser)->get(localized_route('meetings.create', $engagement));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('meetings.create', $engagement));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('meetings.store', $engagement), [
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
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('Meeting 1');
    $response->assertSee('Tuesday, November 15, 2022 9:00 AM');

    expect($engagement->fresh()->meeting_dates)->toEqual('November 15, 2022');
});

test('meetings can be edited', function () {
    $user = User::where('context', 'regulated-organization')->first();
    $otherUser = User::where('context', 'individual')->first();
    $regulatedOrganization = $user->regulated_organization;
    $project = $regulatedOrganization->projects->first();
    $engagement = $project->engagements->first();
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

    $response = $this->actingAs($otherUser)->get(localized_route('meetings.edit', ['meeting' => $meeting, 'engagement' => $engagement]));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('meetings.edit', ['meeting' => $meeting, 'engagement' => $engagement]));
    $response->assertOk();

    $meeting = $meeting->fresh();
    expect($engagement->meeting_dates)->toEqual('November 15–December 15, 2022');

    $response = $this->actingAs($user)->put(localized_route('meetings.update', ['meeting' => $meeting, 'engagement' => $engagement]), [
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
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $meeting = $meeting->fresh();
    $engagement = $engagement->fresh();
    expect($meeting->meeting_types)->toHaveCount(3);
    expect($engagement->meeting_dates)->toEqual('December 6–15, 2022');
    expect($engagement->display_meeting_types)->toContain('In person');
    expect($engagement->display_meeting_types)->toContain('Virtual – web conference');
});

test('meetings can be deleted', function () {
    $this->seed(DisabilityTypeSeeder::class);

    $user = User::where('context', 'regulated-organization')->first();
    $otherUser = User::where('context', 'individual')->first();
    $regulatedOrganization = $user->regulated_organization;
    $project = $regulatedOrganization->projects->first();
    $engagement = $project->engagements->first();
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

    $response = $this->actingAs($otherUser)->delete(localized_route('meetings.destroy', ['meeting' => $meeting, 'engagement' => $engagement]));
    $response->assertForbidden();

    $response = $this->actingAs($user)->delete(localized_route('meetings.destroy', ['meeting' => $meeting, 'engagement' => $engagement]));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('No meetings found.');
});
