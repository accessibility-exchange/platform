<?php

use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Support\Carbon;

use function Pest\Laravel\artisan;

test('app:migrate-data command runs successfully', function () {
    artisan('app:migrate-data')
        ->assertSuccessful()
        ->expectsOutputToContain('Completed')
        ->expectsOutputToContain('Skipped');

    // clean up
    artisan('migrate:fresh');
});

test('app:migrate-data command can list migrations', function () {
    artisan('app:migrate-data --list')
        ->assertSuccessful()
        ->expectsOutputToContain('Version added');

    // clean up
    artisan('migrate:fresh');
});

test('app:migrate-data command can run migrations starting at a specific version', function () {
    artisan('app:migrate-data --from=1.0.0')
        ->assertSuccessful()
        ->expectsOutputToContain('Skipped   0')
        ->doesntExpectOutputToContain('Completed 0');

    artisan('app:migrate-data --from=100000')
        ->assertSuccessful()
        ->expectsOutputToContain('Completed 0')
        ->doesntExpectOutputToContain('Skipped   0');

    // clean up
    artisan('migrate:fresh');
});

test('app:migrate-data command can run with verbose logging', function () {
    artisan('app:migrate-data --from=1.6.0 -v')
        ->assertSuccessful()
        ->expectsOutputToContain('Run migration -');

    // clean up
    artisan('migrate:fresh');
});

test('enableEngagementNotificationsMigration - Migrates Individual users and orgs data successfully', function () {
    $user = User::factory()->create([
        'notification_settings' => ['other' => 'test'],
    ]);

    $org = Organization::factory()->create([
        'notification_settings' => ['other' => 'test'],
    ]);

    artisan('app:migrate-data --migration=EnableEngagementNotifications')->assertSuccessful();

    $user->refresh();
    expect($user->notification_settings->get('other'))->toBeNull();
    expect($user->notification_settings->get('engagements'))->toBe('1');
    expect($user->notification_settings->count())->toBe(1);

    $org->refresh();
    expect($org->notification_settings->get('other'))->toBeNull();
    expect($org->notification_settings->get('engagements'))->toBe('1');
    expect($org->notification_settings->count())->toBe(1);

    // clean up
    artisan('migrate:fresh');
});

test('enableEngagementNotificationsMigration - Only migrates Individual users and orgs', function () {
    $user = User::factory()->create([
        'context' => UserContext::Organization->value,
    ]);

    $fro = RegulatedOrganization::factory()->create();

    artisan('app:migrate-data --migration=EnableEngagementNotifications')->assertSuccessful();

    $user->refresh();
    expect($user->notification_settings->get('engagements'))->toBeNull();
    expect($user->notification_settings->count())->toBe(0);

    $fro->refresh();
    expect($fro->notification_settings->get('engagements'))->toBeNull();
    expect($fro->notification_settings->count())->toBe(0);

    // clean up
    artisan('migrate:fresh');
});

test('enableEngagementNotificationsMigration - skips when notifications_settings are already updated', function () {
    $user = User::factory()->create([
        'notification_settings' => ['engagements' => '0'],
    ]);

    $org = User::factory()->create([
        'notification_settings' => ['engagements' => '0'],
    ]);

    artisan('app:migrate-data --migration=EnableEngagementNotifications')->assertSuccessful();

    $user->refresh();
    expect($user->notification_settings->get('engagements'))->toBe('0');
    expect($user->notification_settings->count())->toBe(1);

    $org->refresh();
    expect($org->notification_settings->get('engagements'))->toBe('0');
    expect($org->notification_settings->count())->toBe(1);

    // clean up
    artisan('migrate:fresh');
});

test('schemalessPromptsMigration - migrates user data successfully', function () {
    $datetime = now();

    $user = User::factory()->create([
        'dismissed_customize_prompt_at' => $datetime,
    ]);

    $org = Organization::factory()->create([
        'dismissed_invite_prompt_at' => $datetime,
    ]);

    $regulatedOrg = RegulatedOrganization::factory()->create([
        'dismissed_invite_prompt_at' => $datetime,
    ]);

    artisan('app:migrate-data --migration=SchemalessPrompts')->assertSuccessful();

    $user->refresh();
    $org->refresh();
    $regulatedOrg->refresh();

    expect(new Carbon($user->prompts->dismissed_customize_prompt_at)->toString())->toBe($datetime->toString());
    expect($user->dismissed_customize_prompt_at)->toBeNull();

    expect(new Carbon($org->prompts->dismissed_invite_prompt_at)->toString())->toBe($datetime->toString());
    expect($org->dismissed_invite_prompt_at)->toBeNull();

    expect(new Carbon($regulatedOrg->prompts->dismissed_invite_prompt_at)->toString())->toBe($datetime->toString());
    expect($regulatedOrg->dismissed_invite_prompt_at)->toBeNull();
});
