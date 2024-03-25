<?php

use App\Enums\Compensation;
use App\Enums\EngagementRecruitment;
use App\Enums\EngagementSignUpStatus;
use App\Enums\MeetingType;
use App\Enums\ProjectInitiator;
use App\Enums\SeekingForEngagement;
use App\Livewire\BrowseEngagements;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\MatchingStrategy;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

test('searchQuery property change', function () {
    $engagementName = 'Sample Engagement';
    $engagement = Engagement::factory()->create(['name->en' => $engagementName]);
    $engagement->loadMissing('project.projectable');

    $this->actingAs(User::factory()->create());
    livewire(BrowseEngagements::class, ['searchQuery' => ''])
        ->assertSee($engagementName)
        ->set('searchQuery', 'Test')
        ->assertDontSee($engagementName)
        ->set('searchQuery', 'Sample')
        ->assertSee($engagementName)
        ->set('searchQuery', 'sample')
        ->assertSee($engagementName)
        ->set('searchQuery', "{$engagement->project->projectable->name}-test")
        ->assertDontSee($engagementName)
        ->set('searchQuery', $engagement->project->projectable->name)
        ->assertSee($engagementName);
});

test('sign up/statuses property change', function () {
    $openEngagementName = 'Open Engagement';
    $closedEngagementName = 'Closed Engagement';
    $noSignUpDateEngagement = 'No Sign Up Date Engagement';

    Engagement::factory()->create([
        'name->en' => $openEngagementName,
        'signup_by_date' => Carbon::now()->addDays(5),
    ]);

    Engagement::factory()->create([
        'name->en' => $closedEngagementName,
        'signup_by_date' => Carbon::now()->subDays(5),
    ]);

    Engagement::factory()->create([
        'name->en' => $noSignUpDateEngagement,
    ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['statuses' => []]);
    $engagements->assertSee($openEngagementName);
    $engagements->assertSee($closedEngagementName);
    $engagements->assertSee($noSignUpDateEngagement);

    $engagements->set('statuses', [EngagementSignUpStatus::Open->value]);
    $engagements->assertSee($openEngagementName);
    $engagements->assertDontSee($closedEngagementName);
    $engagements->assertDontSee($noSignUpDateEngagement);

    $engagements->set('statuses', [EngagementSignUpStatus::Closed->value]);
    $engagements->assertDontSee($openEngagementName);
    $engagements->assertSee($closedEngagementName);
    $engagements->assertDontSee($noSignUpDateEngagement);

    $engagements->set('statuses', array_column(EngagementSignUpStatus::cases(), 'value'));
    $engagements->assertSee($openEngagementName);
    $engagements->assertSee($closedEngagementName);
    $engagements->assertDontSee($noSignUpDateEngagement);
});

test('format property change', function (array $filter = [], array $toSee = [], array $dontSee = []) {
    foreach (array_merge($toSee, $dontSee) as $format => $name) {
        Engagement::factory()->create([
            'name' => $name,
            'format' => $format,
        ]);
    }

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['formats' => $filter]);

    foreach ($toSee as $engagementToSee) {
        $engagements->assertSee($engagementToSee);
    }

    foreach ($dontSee as $engagementDontSee) {
        $engagements->assertDontSee($engagementDontSee);
    }
})->with('browseEngagementsFormat');

test('seekings property change', function () {
    $seekings = array_column(SeekingForEngagement::cases(), 'value');

    Engagement::factory()->create([
        'name->en' => SeekingForEngagement::Participants->value.' - Engagement',
        'recruitment' => 'open-call',
    ]);

    Engagement::factory()->create([
        'name->en' => SeekingForEngagement::Connectors->value.' - Engagement',
        'recruitment' => 'connector',
        'extra_attributes' => ['seeking_community_connector' => true],
    ]);

    Engagement::factory()->create([
        'name->en' => SeekingForEngagement::Organizations->value.' - Engagement',
        'recruitment' => 'connector',
        'who' => 'organization',
    ]);

    // Ensure all engagements are shown when no seekings specified
    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['seekings' => []]);

    foreach ($seekings as $seeking) {
        $engagements->assertSee("{$seeking} - Engagement");
    }

    // Ensure only the engagement for the requested seeking is returned
    foreach ($seekings as $seeking) {
        $engagements->set('seekings', [$seeking]);

        $engagements->assertSee("{$seeking} - Engagement");

        foreach ($seekings as $otherSeeking) {
            if ($otherSeeking !== $seeking) {
                $engagements->assertDontSee("{$otherSeeking} - Engagement");
            }
        }
    }

    // Ensure all engagements are shown when all seekings specified
    $engagements->set('seekings', $seekings);

    foreach ($seekings as $seeking) {
        $engagements->assertSee("{$seeking} - Engagement");
    }
});

test('initiators property change', function () {
    foreach (ProjectInitiator::labels() as $initiator => $label) {
        $initiator = 'App\Models\\'.Str::studly($initiator);
        Engagement::factory()
            ->for(
                Project::factory()
                    ->for($initiator::factory(), 'projectable')
            )
            ->create(['name->en' => $label.' - Engagement']);
    }

    $communityOrganizationEngagementName = ProjectInitiator::labels()[ProjectInitiator::Organization->value].' - Engagement';
    $regulatedOrganizationEngagementName = ProjectInitiator::labels()[ProjectInitiator::RegulatedOrganization->value].' - Engagement';

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['initiators' => []]);
    $engagements->assertSee($communityOrganizationEngagementName);
    $engagements->assertSee($regulatedOrganizationEngagementName);

    $engagements->set('initiators', [ProjectInitiator::Organization->value]);
    $engagements->assertSee($communityOrganizationEngagementName);
    $engagements->assertDontSee($regulatedOrganizationEngagementName);

    $engagements->set('initiators', [ProjectInitiator::RegulatedOrganization->value]);
    $engagements->assertDontSee($communityOrganizationEngagementName);
    $engagements->assertSee($regulatedOrganizationEngagementName);

    $engagements->set('initiators', array_column(ProjectInitiator::cases(), 'value'));
    $engagements->assertSee($communityOrganizationEngagementName);
    $engagements->assertSee($regulatedOrganizationEngagementName);
});

test('seekingGroups property change', function () {
    $engagementSeekingDeafExperienceName = 'Engagement Seeking Deaf Experience';

    $disabilityTypeDeafMatchingStrategy = MatchingStrategy::factory()
        ->for(Engagement::factory()->state([
            'name' => $engagementSeekingDeafExperienceName,
        ]), 'matchable')
        ->has(Identity::factory()->state([
            'name' => [
                'en' => 'Deaf',
                'fr' => __('Deaf', [], 'fr'),
            ],
            'clusters' => ['disability-and-deaf'],
        ])
        )
        ->create();

    $engagementSeekingCognitiveDisabilityExperienceName = 'Engagement Seeking Cognitive Disability Experience';

    $disabilityTypeCognitiveMatchingStrategy = MatchingStrategy::factory()
        ->for(Engagement::factory()->state([
            'name' => $engagementSeekingCognitiveDisabilityExperienceName,
        ]), 'matchable')
        ->has(
            Identity::factory()->state([
                'name' => [
                    'en' => 'Cognitive disabilities',
                    'fr' => __('Cognitive disabilities', [], 'fr'),
                ],
                'description' => [
                    'en' => 'Includes traumatic brain injury, memory difficulties, dementia',
                    'fr' => __('Includes traumatic brain injury, memory difficulties, dementia', [], 'fr'),
                ],
                'clusters' => ['disability-and-deaf'],
            ])
        )
        ->create();

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['seekingGroups' => []]);
    $engagements->assertSee($engagementSeekingDeafExperienceName);
    $engagements->assertSee($engagementSeekingCognitiveDisabilityExperienceName);

    $engagements->set('seekingGroups', [$disabilityTypeDeafMatchingStrategy->identities()->first()->id]);
    $engagements->assertSee($engagementSeekingDeafExperienceName);
    $engagements->assertDontSee($engagementSeekingCognitiveDisabilityExperienceName);

    $engagements->set('seekingGroups', [$disabilityTypeCognitiveMatchingStrategy->identities()->first()->id]);
    $engagements->assertDontSee($engagementSeekingDeafExperienceName);
    $engagements->assertSee($engagementSeekingCognitiveDisabilityExperienceName);

    $engagements->set('seekingGroups', [$disabilityTypeDeafMatchingStrategy->identities()->first()->id, $disabilityTypeCognitiveMatchingStrategy->identities()->first()->id]);
    $engagements->assertSee($engagementSeekingDeafExperienceName);
    $engagements->assertSee($engagementSeekingCognitiveDisabilityExperienceName);
});

test('meetingTypes property change', function () {
    $inPersonInterviewEngagementName = 'In person Interview';
    Engagement::factory()->create([
        'name' => $inPersonInterviewEngagementName,
        'extra_attributes' => ['format' => 'interviews'],
        'meeting_types' => [MeetingType::InPerson->value],
    ]);

    $virtualWorkshopEngagementName = 'Virtual Workshop';
    Engagement::factory()
        ->has(Meeting::factory()->state([
            'meeting_types' => [MeetingType::WebConference->value],
        ]))
        ->create([
            'name' => $virtualWorkshopEngagementName,
            'extra_attributes' => ['format' => 'workshop'],
            'meeting_types' => null,
        ]);

    $phoneFocusGroupEngagementName = 'Phone Focus Group';
    Engagement::factory()
        ->has(Meeting::factory()->state([
            'meeting_types' => [MeetingType::Phone->value],
        ]))
        ->create([
            'name' => $phoneFocusGroupEngagementName,
            'extra_attributes' => ['format' => 'focus-group'],
            'meeting_types' => null,
        ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['meetingTypes' => []]);
    $engagements->assertSee($inPersonInterviewEngagementName);
    $engagements->assertSee($virtualWorkshopEngagementName);
    $engagements->assertSee($phoneFocusGroupEngagementName);

    $engagements->set('meetingTypes', [MeetingType::InPerson->value]);
    $engagements->assertSee($inPersonInterviewEngagementName);
    $engagements->assertDontSee($virtualWorkshopEngagementName);
    $engagements->assertDontSee($phoneFocusGroupEngagementName);

    $engagements->set('meetingTypes', [MeetingType::WebConference->value]);
    $engagements->assertDontSee($inPersonInterviewEngagementName);
    $engagements->assertSee($virtualWorkshopEngagementName);
    $engagements->assertDontSee($phoneFocusGroupEngagementName);

    $engagements->set('meetingTypes', [MeetingType::Phone->value]);
    $engagements->assertDontSee($inPersonInterviewEngagementName);
    $engagements->assertDontSee($virtualWorkshopEngagementName);
    $engagements->assertSee($phoneFocusGroupEngagementName);

    $engagements->set('meetingTypes', array_column(MeetingType::cases(), 'value'));
    $engagements->assertSee($inPersonInterviewEngagementName);
    $engagements->assertSee($virtualWorkshopEngagementName);
    $engagements->assertSee($phoneFocusGroupEngagementName);
});

test('compensations property change', function () {
    $paidEngagementName = 'Paid Engagement';
    Engagement::factory()->create([
        'name' => $paidEngagementName,
        'paid' => true,
    ]);

    $volunteerEngagementName = 'Volunteer Engagement';
    Engagement::factory()->create([
        'name' => $volunteerEngagementName,
        'paid' => false,
    ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['compensations' => []]);
    $engagements->assertSee($paidEngagementName);
    $engagements->assertSee($volunteerEngagementName);

    $engagements->set('compensations', [Compensation::Paid->value]);
    $engagements->assertSee($paidEngagementName);
    $engagements->assertDontSee($volunteerEngagementName);

    $engagements->set('compensations', [Compensation::Volunteer->value]);
    $engagements->assertDontSee($paidEngagementName);
    $engagements->assertSee($volunteerEngagementName);

    $engagements->set('compensations', array_column(Compensation::cases(), 'value'));
    $engagements->assertSee($paidEngagementName);
    $engagements->assertSee($volunteerEngagementName);
});

test('sectors property change', function () {
    $regulatedPrivateEngagementName = 'Regulated Private Sector Engagement';
    $private = RegulatedOrganization::factory()
        ->hasSectors(1, [
            'name' => [
                'en' => 'Federally Regulated private sector',
                'fr' => __('Federally Regulated private sector', [], 'fr'),
            ],
            'description' => [
                'en' => 'Banks, federal transportation network (airlines, rail, road and marine transportation providers that cross provincial or international borders), atomic energy, postal and courier services, the broadcasting and telecommunications sectors',
                'fr' => __('Banks, federal transportation network (airlines, rail, road and marine transportation providers that cross provincial or international borders), atomic energy, postal and courier services, the broadcasting and telecommunications sectors', [], 'fr'),
            ],
        ])
        ->has(Project::factory()->hasEngagements(1, [
            'name' => $regulatedPrivateEngagementName,
        ]))
        ->create();

    $parliamentaryEngagementName = 'Parliamentary Engagement';
    $parliamentary = RegulatedOrganization::factory()
        ->hasSectors(1, [
            'name' => [
                'en' => 'Parliamentary entities',
                'fr' => __('Parliamentary entities', [], 'fr'),
            ],
            'description' => [
                'en' => 'House of Commons, Senate, Library of Parliament, Parliamentary Protective Service',
                'fr' => __('House of Commons, Senate, Library of Parliament, Parliamentary Protective Service', [], 'fr'),
            ],
        ])
        ->has(Project::factory()->hasEngagements(1, [
            'name' => $parliamentaryEngagementName,
        ]))
        ->create();

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['sectors' => []]);
    $engagements->assertSee($regulatedPrivateEngagementName);
    $engagements->assertSee($parliamentaryEngagementName);

    $engagements->set('sectors', [$private->sectors->first()->id]);
    $engagements->assertSee($regulatedPrivateEngagementName);
    $engagements->assertDontSee($parliamentaryEngagementName);

    $engagements->set('sectors', [$parliamentary->sectors()->first()->id]);
    $engagements->assertDontSee($regulatedPrivateEngagementName);
    $engagements->assertSee($parliamentaryEngagementName);

    $engagements->set('sectors', [$private->sectors->first()->id, $parliamentary->sectors()->first()->id]);
    $engagements->assertSee($regulatedPrivateEngagementName);
    $engagements->assertSee($parliamentaryEngagementName);
});

test('impacts property change', function () {
    $employmentImpactEngagementName = 'Employment Impact Engagement';
    $employment = Project::factory()
        ->hasAttached(Impact::factory()->state([
            'name' => [
                'en' => 'Employment',
                'fr' => __('Employment', [], 'fr'),
            ],
        ]))
        ->hasEngagements(1, [
            'name' => $employmentImpactEngagementName,
        ])
        ->create();

    $communicationImpactEngagementName = 'Communication Impact Engagement';
    $communication = Project::factory()
        ->hasAttached(Impact::factory()->state([
            'name' => [
                'en' => 'Communications',
                'fr' => __('Communications', [], 'fr'),
            ],
        ]))
        ->hasEngagements(1, [
            'name' => $communicationImpactEngagementName,
        ])
        ->create();

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['impacts' => []]);
    $engagements->assertSee($employmentImpactEngagementName);
    $engagements->assertSee($communicationImpactEngagementName);

    $engagements->set('impacts', [$employment->impacts->first()->id]);
    $engagements->assertSee($employmentImpactEngagementName);
    $engagements->assertDontSee($communicationImpactEngagementName);

    $engagements->set('impacts', [$communication->impacts->first()->id]);
    $engagements->assertDontSee($employmentImpactEngagementName);
    $engagements->assertSee($communicationImpactEngagementName);

    $engagements->set('impacts', [$employment->impacts->first()->id, $communication->impacts->first()->id]);
    $engagements->assertSee($employmentImpactEngagementName);
    $engagements->assertSee($communicationImpactEngagementName);
});

test('recruitmentMethods property change', function () {
    $openCallEngagementName = 'Open Call Engagement';
    Engagement::factory()->create([
        'name' => $openCallEngagementName,
        'recruitment' => EngagementRecruitment::OpenCall->value,
    ]);

    $connectorEngagementName = 'Connector Engagement';
    Engagement::factory()->create([
        'name' => $connectorEngagementName,
        'recruitment' => EngagementRecruitment::CommunityConnector->value,
    ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['recruitmentMethods' => []]);
    $engagements->assertSee($openCallEngagementName);
    $engagements->assertSee($connectorEngagementName);

    $engagements->set('recruitmentMethods', [EngagementRecruitment::OpenCall->value]);
    $engagements->assertSee($openCallEngagementName);
    $engagements->assertDontSee($connectorEngagementName);

    $engagements->set('recruitmentMethods', [EngagementRecruitment::CommunityConnector->value]);
    $engagements->assertDontSee($openCallEngagementName);
    $engagements->assertSee($connectorEngagementName);

    $engagements->set('recruitmentMethods', array_column(EngagementRecruitment::cases(), 'value'));
    $engagements->assertSee($openCallEngagementName);
    $engagements->assertSee($connectorEngagementName);
});

test('locations property change', function () {
    $regionSpecificEngagementName = 'Region Specific Engagement';
    Engagement::factory()->create(['name' => $regionSpecificEngagementName])
        ->matchingStrategy->update([
            'regions' => ['AB'],
        ]);

    $locationSpecificEngagementName = 'Location Specific Engagement';
    Engagement::factory()->create(['name' => $locationSpecificEngagementName])
        ->matchingStrategy->update([
            'locations' => [
                ['region' => 'AB', 'locality' => 'Edmonton'],
                ['region' => 'ON', 'locality' => 'Toronto'],
            ],
        ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['locations' => []]);
    $engagements->assertSee($regionSpecificEngagementName);
    $engagements->assertSee($locationSpecificEngagementName);

    $engagements->set('locations', ['AB']);
    $engagements->assertSee($regionSpecificEngagementName);
    $engagements->assertSee($locationSpecificEngagementName);

    $engagements->set('locations', ['ON']);
    $engagements->assertDontSee($regionSpecificEngagementName);
    $engagements->assertSee($locationSpecificEngagementName);

    $engagements->set('locations', ['AB', 'ON']);
    $engagements->assertSee($regionSpecificEngagementName);
    $engagements->assertSee($locationSpecificEngagementName);
});

test('selectNone', function () {
    $closedEngagementName = 'Closed Engagement';
    Engagement::factory()->create([
        'name->en' => $closedEngagementName,
        'signup_by_date' => now()->subDays(5),
        'recruitment' => EngagementRecruitment::CommunityConnector->value,
    ]);

    $openCallEngagementName = 'Open Call Engagement';
    Engagement::factory()->create([
        'name->en' => $openCallEngagementName,
        'signup_by_date' => now()->addDays(5),
        'recruitment' => EngagementRecruitment::OpenCall->value,
    ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['statuses' => [EngagementSignUpStatus::Closed->value], 'recruitmentMethods' => [EngagementRecruitment::CommunityConnector->value]]);
    $engagements->assertSee($closedEngagementName);
    $engagements->assertDontSee($openCallEngagementName);

    $engagements->call('selectNone');
    $engagements->assertSee($closedEngagementName);
    $engagements->assertSee($openCallEngagementName);
});

test('no engagements found', function () {
    $noEngagementsFound = __('No engagements found.');
    $noEngagementFoundCountMessage = '0 engagements match your applied filters.';
    $oneEngagementFoundCountMessage = '1 engagement matches your applied filters.';
    $foundEngagementName = 'Found Engagement';
    Engagement::factory()->create([
        'name->en' => $foundEngagementName,
        'signup_by_date' => now()->subDays(5),
    ]);

    $this->actingAs(User::factory()->create());
    $engagements = livewire(BrowseEngagements::class, ['statuses' => [EngagementSignUpStatus::Closed->value]]);
    $engagements->assertSee($oneEngagementFoundCountMessage);
    $engagements->assertDontSee($noEngagementFoundCountMessage);
    $engagements->assertSee($foundEngagementName);
    $engagements->assertDontSee($noEngagementsFound);

    $engagements->set('statuses', [EngagementSignUpStatus::Open->value]);
    $engagements->assertSee($noEngagementFoundCountMessage);
    $engagements->assertDontSee($oneEngagementFoundCountMessage);
    $engagements->assertDontSee($foundEngagementName);
    $engagements->assertSee($noEngagementsFound);
});
