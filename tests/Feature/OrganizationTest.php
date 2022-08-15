<?php

use App\Models\AgeBracket;
use App\Models\AreaType;
use App\Models\DisabilityType;
use App\Models\Engagement;
use App\Models\EthnoracialIdentity;
use App\Models\Impact;
use App\Models\IndigenousIdentity;
use App\Models\LivedExperience;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Hearth\Models\Invitation;
use Hearth\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use function Pest\Faker\faker;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;
use Tests\RequestFactories\UpdateOrganizationRequestFactory;

uses(RefreshDatabase::class);

test('users can create organizations', function () {
    $user = User::factory()->create(['context' => 'organization', 'signed_language' => 'ase']);

    $response = $this->actingAs($user)->get(localized_route('organizations.show-type-selection'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('organizations.store-type'), [
        'type' => 'representative',
    ]);
    $response->assertRedirect(localized_route('organizations.create'));
    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('type', 'representative');

    $response = $this->actingAs($user)->get(localized_route('organizations.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('organizations.create'), [
        'name' => ['en' => $user->name.' Foundation'],
        'type' => 'representative',
    ]);
    $response->assertSessionHasNoErrors();
    $organization = Organization::where('name->en', $user->name.' Foundation')->first();
    $response->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    expect($organization->working_languages)->toContain('ase');

    $response = $this->actingAs($user)->get(localized_route('organizations.show-role-selection', $organization));
    $response->assertOk();
    $response = $this->actingAs($user)->from(localized_route('organizations.show-role-selection', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['consultant'],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isConsultant())->toBeTrue();

    $response = $this->actingAs($user)->from(localized_route('organizations.show-role-selection', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['connector'],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isConnector())->toBeTrue();

    $response = $this->actingAs($user)->from(localized_route('organizations.show-role-selection', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['participant'],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isParticipant())->toBeTrue();

    $response = $this->actingAs($user)->get(localized_route('organizations.show-role-edit', $organization));
    $response->assertOk();
    $response->assertSee('<input  type="checkbox" name="roles[]" id="roles-participant" value="participant" aria-describedby="roles-participant-hint" checked  />', false);

    $response = $this->actingAs($user)->from(localized_route('organizations.show-role-edit', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['consultant'],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isConsultant())->toBeTrue();

    expect($user->isMemberOf($organization))->toBeTrue();
    expect($user->memberships)->toHaveCount(1);

    $response = $this->actingAs($user)->get(localized_route('organizations.show-language-selection', $organization));
    $response->assertOk();

    $response = $this->actingAs($user)->from(localized_route('organizations.show-language-selection', $organization))->post(localized_route('organizations.store-languages', $organization), [
        'languages' => ['en', 'fr', 'iu'],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', $organization));
});

test('users with admin role can edit and publish organizations', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create(['contact_person_name' => faker()->name]);

    $response = $this->actingAs($user)->get(localized_route('organizations.edit', $organization));
    $response->assertOk();

    UpdateOrganizationRequestFactory::new()->without(['name'])->fake();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'name' => ['en' => $organization->name],
        'save_and_next' => 1,
    ]);

    expect($organization->fresh()->social_links)->toBeArray()->toBeEmpty();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', [
        'organization' => $organization,
        'step' => 2,
    ]));

    $response = $this->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization, 'step' => 1]))
        ->put(localized_route('organizations.update', $organization), [
            'name' => ['en' => $organization->name],
            'publish' => 1,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.show', $organization));

    expect($organization->fresh()->checkStatus('published'))->toBeTrue();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization->fresh()), [
        'name' => ['en' => $organization->name],
        'unpublish' => 1,
    ]);
    $response->assertSessionHasNoErrors();
    expect($organization->fresh()->checkStatus('published'))->toBeFalse();

    $organization->update([
        'languages' => null,
    ]);

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization->fresh()), [
        'name' => ['en' => $organization->name],
        'publish' => 1,
    ]);
    $response->assertForbidden();

    expect($organization->fresh()->checkStatus('published'))->toBeFalse();
});

test('users with admin role can edit organization constituencies', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('organizations.update-constituencies', $organization->fresh()), [
        'lived_experiences' => [LivedExperience::where('name->en', 'People who experience disabilities')->first()->id],
        'base_disability_type' => 'specific_disabilities',
        'disability_types' => [],
        'other_disability' => true,
        'other_disability_type' => ['en' => 'Something else'],
        'area_types' => [AreaType::first()->id],
        'has_indigenous_identities' => true,
        'indigenous_identities' => [IndigenousIdentity::first()->id],
        'refugees_and_immigrants' => true,
        'has_gender_and_sexual_identities' => true,
        'gender_and_sexual_identities' => ['women', 'nb-gnc-fluid-people', 'trans-people', '2slgbtqiaplus-people'],
        'has_age_brackets' => true,
        'age_brackets' => [AgeBracket::first()->id],
        'has_ethnoracial_identities' => true,
        'ethnoracial_identities' => [EthnoracialIdentity::first()->id],
        'constituent_languages' => ['en', 'fr'],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization = $organization->fresh();

    expect($organization->livedExperiences)->toHaveCount(1);
    expect($organization->disabilityTypes)->toHaveCount(0);
    expect($organization->base_disability_type)->toEqual('specific_disabilities');
    expect($organization->other_disability_type)->toEqual('Something else');
    expect($organization->areaTypes)->toHaveCount(1);
    expect($organization->indigenousIdentities)->toHaveCount(1);
    expect($organization->genderIdentities)->toHaveCount(4);
    expect($organization->constituencies)->toHaveCount(3);
    expect($organization->ageBrackets)->toHaveCount(1);
    expect($organization->ethnoracialIdentities)->toHaveCount(1);
    expect($organization->constituentLanguages)->toHaveCount(2);
    expect($organization->staff_lived_experience)->toEqual('prefer-not-to-answer');

    $response = $this->actingAs($user)->put(localized_route('organizations.update-constituencies', $organization->fresh()), [
        'lived_experiences' => [LivedExperience::where('name->en', 'People who experience disabilities')->first()->id],
        'base_disability_type' => 'specific_disabilities',
        'disability_types' => [DisabilityType::where('name->en', 'Multiple disabilities')->first()->id],
        'area_types' => [AreaType::first()->id],
        'has_indigenous_identities' => false,
        'refugees_and_immigrants' => false,
        'has_gender_and_sexual_identities' => false,
        'has_age_brackets' => false,
        'has_ethnoracial_identities' => false,
        'constituent_languages' => ['en', 'fr'],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization = $organization->fresh();

    expect($organization->livedExperiences)->toHaveCount(1);
    expect($organization->disabilityTypes)->toHaveCount(1);
    expect($organization->base_disability_type)->toEqual('specific_disabilities');
    expect($organization->areaTypes)->toHaveCount(1);
    expect($organization->indigenousIdentities)->toHaveCount(0);
    expect($organization->genderIdentities)->toHaveCount(0);
    expect($organization->constituencies)->toHaveCount(0);
    expect($organization->ageBrackets)->toHaveCount(0);
    expect($organization->ethnoracialIdentities)->toHaveCount(0);
    expect($organization->constituentLanguages)->toHaveCount(2);
    expect($organization->staff_lived_experience)->toEqual('prefer-not-to-answer');

    $response = $this->actingAs($user)->put(localized_route('organizations.update-constituencies', $organization->fresh()), [
        'lived_experiences' => [LivedExperience::where('name->en', 'People who experience disabilities')->first()->id],
        'base_disability_type' => 'cross_disability',
        'area_types' => [AreaType::first()->id],
        'has_indigenous_identities' => false,
        'refugees_and_immigrants' => false,
        'has_gender_and_sexual_identities' => false,
        'has_age_brackets' => false,
        'has_ethnoracial_identities' => false,
        'constituent_languages' => ['en', 'fr'],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization = $organization->fresh();

    expect($organization->base_disability_type)->toEqual('cross_disability');
});

test('users with admin role can edit organization interests', function () {
    $this->seed(ImpactSeeder::class);
    $this->seed(SectorSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('organizations.update-interests', $organization->fresh()), [
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));

    $organization = $organization->fresh();

    expect($organization->sectors)->toHaveCount(0);
    expect($organization->impacts)->toHaveCount(0);

    $response = $this->actingAs($user)->put(localized_route('organizations.update-interests', $organization->fresh()), [
        'impacts' => [Impact::inRandomOrder()->first()->id],
        'sectors' => [Sector::inRandomOrder()->first()->id],
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));

    $organization = $organization->fresh();

    expect($organization->sectors)->toHaveCount(1);
    expect($organization->impacts)->toHaveCount(1);

    $response = $this->actingAs($user)->put(localized_route('organizations.update-interests', $organization->fresh()), [
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));

    $organization = $organization->fresh();

    expect($organization->sectors)->toHaveCount(0);
    expect($organization->impacts)->toHaveCount(0);
});

test('users with admin role can edit organization contact information', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]));
    $response->assertOk();

    $name = faker()->name;

    $response = $this->actingAs($user)->put(localized_route('organizations.update-contact-information', $organization->fresh()), [
        'contact_person_name' => $name,
        'contact_person_email' => Str::slug($name).'@'.faker()->safeEmailDomain,
        'contact_person_phone' => '19024444444',
        'preferred_contact_method' => 'email',
        'save' => 1,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]));

    $organization = $organization->fresh();

    expect($organization->contact_methods)->toContain('email')->toContain('phone');

    expect($organization->primary_contact_point)->toEqual($organization->contact_person_email);
    expect($organization->alternate_contact_point)->toEqual($organization->contact_person_phone->formatForCountry('CA'));
    expect($organization->primary_contact_method)->toEqual("Send an email to {$organization->contact_person_name} at <{$organization->contact_person_email}>.");
    expect($organization->alternate_contact_method)->toEqual($organization->alternate_contact_point);

    $organization->preferred_contact_method = 'phone';
    $organization->save();
    $organization = $organization->fresh();

    expect($organization->primary_contact_point)->toEqual($organization->contact_person_phone->formatForCountry('CA'));
    expect($organization->alternate_contact_point)->toEqual($organization->contact_person_email);
    expect($organization->primary_contact_method)->toEqual("Call {$organization->contact_person_name} at {$organization->contact_person_phone->formatForCountry('CA')}.");
    expect($organization->alternate_contact_method)->toEqual("<{$organization->contact_person_email}>");
});

test('users without admin role cannot edit or publish organizations', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('organizations.edit', $organization));
    $response->assertForbidden();

    UpdateOrganizationRequestFactory::new()->fake();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'name' => ['en' => $organization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'publish' => 1,
    ]);
    $response->assertForbidden();

    expect($organization->checkStatus('published'))->toBeFalse();

    $organization->publish();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'unpublish' => 1,
    ]);
    $response->assertForbidden();

    expect($organization->checkStatus('published'))->toBeTrue();
});

test('non members cannot edit or publish organizations', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $adminUser = User::factory()->create(['context' => 'organization']);

    $organization = Organization::factory()
        ->hasAttached($adminUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('organizations.edit', $organization));
    $response->assertForbidden();

    UpdateOrganizationRequestFactory::new()->fake();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'name' => ['en' => $organization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'publish' => 1,
    ]);
    $response->assertForbidden();

    expect($organization->checkStatus('published'))->toBeFalse();

    $organization->publish();

    $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
        'unpublish' => 1,
    ]);
    $response->assertForbidden();

    expect($organization->checkStatus('published'))->toBeTrue();
});

test('organizations can be translated', function () {
    $organization = Organization::factory()->create();

    $organization->setTranslation('name', 'en', 'Name in English');
    $organization->setTranslation('name', 'fr', 'Name in French');

    $this->assertEquals('Name in English', $organization->name);
    App::setLocale('fr');
    $this->assertEquals('Name in French', $organization->name);

    $this->assertEquals('Name in English', $organization->getTranslation('name', 'en'));
    $this->assertEquals('Name in French', $organization->getTranslation('name', 'fr'));

    $translations = ['en' => 'Name in English', 'fr' => 'Name in French'];

    $this->assertEquals($translations, $organization->getTranslations('name'));

    $this->expectException(AttributeIsNotTranslatable::class);
    $organization->setTranslation('locality', 'en', 'Locality in English');
});

test('users with admin role can update other member roles', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'admin',
        ]);
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot update member roles', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'admin',
        ]);

    $response->assertForbidden();
});

test('only administrator cannot downgrade their role', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $other_user = User::factory()->create(['context' => 'organization']);
    $yet_another_user = User::factory()->create(['context' => 'organization']);

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'admin'])
        ->hasAttached($yet_another_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'member',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.show', $organization));

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($other_user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'member',
        ]);

    $response->assertSessionHasErrors(['role']);
    $response->assertRedirect(localized_route('memberships.edit', $membership));
});

test('users with admin role can invite members', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $organization->id,
            'invitationable_type' => get_class($organization),
            'email' => 'newuser@here.com',
            'role' => 'member',
        ]);

    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot invite members', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $organization->id,
            'invitationable_type' => get_class($organization),
            'email' => 'newuser@here.com',
            'role' => 'member',
        ]);

    $response->assertForbidden();
});

test('users with admin role can cancel invitations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => 'me@here.com',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('invitations.destroy', ['invitation' => $invitation]));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot cancel invitations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => 'me@here.com',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('invitations.destroy', ['invitation' => $invitation]));

    $response->assertForbidden();
});

test('existing members cannot be invited', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $organization->id,
            'invitationable_type' => get_class($organization),
            'email' => $other_user->email,
            'role' => 'member',
        ]);

    $response->assertSessionHasErrorsIn('inviteMember', ['email']);
    $response->assertRedirect(localized_route('organizations.edit', $organization));
});

test('invitation can be accepted', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    $response = $this->actingAs($user)->get($acceptUrl);

    $this->assertTrue($organization->fresh()->hasUserWithEmail($user->email));
    $response->assertRedirect(localized_route('organizations.show', $organization));
});

test('invitation cannot be accepted by user with existing membership', function () {
    $user = User::factory()->create();
    Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $other_organization = Organization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $other_organization->id,
        'invitationable_type' => get_class($other_organization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    $response = $this->from(localized_route('dashboard'))->actingAs($user)->get($acceptUrl);

    $this->assertFalse($other_organization->fresh()->hasUserWithEmail($user->email));
    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('dashboard'));
});

test('invitation cannot be accepted by different user', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    $response = $this->from(localized_route('dashboard'))->actingAs($other_user)->get($acceptUrl);

    $this->assertFalse($organization->fresh()->hasUserWithEmail($user->email));
    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('dashboard'));
});

test('users with admin role can remove members', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('memberships.destroy', $membership));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot remove members', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('memberships.destroy', $membership));

    $response->assertForbidden();
});

test('only administrator cannot remove themself', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('memberships.destroy', $membership));

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('organizations.edit', $organization));
});

test('users with admin role can delete organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users with admin role cannot delete organizations with wrong password', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('organizations.edit', $organization));
});

test('users without admin role cannot delete organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('non members cannot delete organizations', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $other_organization = Organization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $other_organization))->delete(localized_route('organizations.destroy', $other_organization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('users can view organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['working_languages' => ['en', 'ase']]);

    $response = $this->actingAs($user)->get(localized_route('organizations.index'));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertOk();
});

test('guests cannot view organizations', function () {
    $organization = Organization::factory()->create();

    $response = $this->get(localized_route('organizations.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('organizations.show', $organization));
    $response->assertRedirect(localized_route('login'));
});

test('organizational relationships to projects can be derived from both projects and engagements', function () {
    $organization = Organization::factory()->create(['roles' => ['consultant', 'connector', 'participant']]);

    $organization = $organization->fresh();

    $consultingProject = Project::factory()->create([
        'organizational_consultant_id' => $organization->id,
    ]);

    $consultingEngagement = Engagement::factory()->create([
        'organizational_consultant_id' => $organization->id,
    ]);

    expect($consultingEngagement->organizationalConsultant->id)->toEqual($organization->id);

    $consultingEngagementProject = $consultingEngagement->project;

    $connectingEngagement = Engagement::factory()->create([
        'organizational_connector_id' => $organization->id,
    ]);

    expect($connectingEngagement->organizationalConnector->id)->toEqual($organization->id);

    $connectingEngagementProject = $connectingEngagement->project;

    $participatingEngagement = Engagement::factory()->create();

    $participatingEngagement->organizationalParticipants()->attach($organization->id, ['status' => 'confirmed']);

    $participatingEngagement = $participatingEngagement->fresh();

    expect($participatingEngagement->confirmedOrganizationalParticipants->pluck('id'))->toContain($organization->id);

    $participatingEngagementProject = $participatingEngagement->project;

    expect($organization->contractedProjects->pluck('id')->toArray())
        ->toHaveCount(3)
        ->toContain($connectingEngagementProject->id)
        ->toContain($consultingEngagementProject->id)
        ->toContain($consultingProject->id);

    expect($organization->participatingProjects->pluck('id')->toArray())
        ->toHaveCount(1)
        ->toContain($participatingEngagementProject->id);
});
