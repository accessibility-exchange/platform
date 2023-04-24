<?php

use App\Http\Livewire\AllProjects;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\MatchingStrategy;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use Carbon\Carbon;
use Database\Seeders\IdentitySeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;

test('test searchQuery property change', function () {
    $sampleProjectName = 'Sample Project';
    Project::factory()->create(['name->en' => $sampleProjectName]);
    $allProjects = $this->livewire(AllProjects::class, ['searchQuery' => ''])
        ->assertSee($sampleProjectName)
        ->set('searchQuery', 'Test')
        ->assertDontSee($sampleProjectName)
        ->set('searchQuery', 'Sample')
        ->assertSee($sampleProjectName)
        ->set('searchQuery', 'sample')
        ->assertSee($sampleProjectName);
});

test('test statuses property change', function () {
    $upComingProjectName = 'Upcoming Project';
    $inProgressProjectName = 'In progress Project';
    $completedProjectName = 'Completed Project';
    Project::factory()->create([
        'name->en' => $upComingProjectName,
        'start_date' => Carbon::now()->addDays(5),
    ]);

    Project::factory()->create([
        'name->en' => $inProgressProjectName,
        'start_date' => Carbon::now()->subDays(5),
        'end_date' => Carbon::now()->addDays(5),
    ]);

    Project::factory()->create([
        'name->en' => $completedProjectName,
        'end_date' => Carbon::now()->subDays(5),
    ]);

    $allProjects = $this->livewire(AllProjects::class, ['statuses' => []]);
    $allProjects->assertSee($upComingProjectName);
    $allProjects->assertSee($inProgressProjectName);
    $allProjects->assertSee($completedProjectName);

    $allProjects->set('statuses', ['upcoming']);
    $allProjects->assertSee($upComingProjectName);
    $allProjects->assertDontSee($inProgressProjectName);
    $allProjects->assertDontSee($completedProjectName);

    $allProjects->set('statuses', ['inProgress']);
    $allProjects->assertDontSee($upComingProjectName);
    $allProjects->assertSee($inProgressProjectName);
    $allProjects->assertDontSee($completedProjectName);

    $allProjects->set('statuses', ['completed']);
    $allProjects->assertDontSee($upComingProjectName);
    $allProjects->assertDontSee($inProgressProjectName);
    $allProjects->assertSee($completedProjectName);

    $allProjects->set('statuses', ['upcoming', 'inProgress', 'completed']);
    $allProjects->assertSee($upComingProjectName);
    $allProjects->assertSee($inProgressProjectName);
    $allProjects->assertSee($completedProjectName);
});

test('test seekings property change', function () {
    $projectSeekingParticipantsName = 'Project Seeking Participants';
    $projectSeekingParticipants = Project::factory()->create([
        'name->en' => $projectSeekingParticipantsName,
    ]);

    $openCallEngagement = Engagement::factory()->create([
        'recruitment' => 'open-call',
        'project_id' => $projectSeekingParticipants->id,
    ]);

    $projectSeekingConnectorsName = 'Project Seeking Connectors';
    $projectSeekingConnectors = Project::factory()->create([
        'name->en' => $projectSeekingConnectorsName,
    ]);

    $connectorEngagement = Engagement::factory()->create([
        'recruitment' => 'connector',
        'project_id' => $projectSeekingConnectors->id,
        'extra_attributes' => ['seeking_community_connector' => true],
    ]);

    $projectSeekingOrganizationsName = 'Project Seeking Organizations';
    $projectSeekingOrganizations = Project::factory()->create([
        'name->en' => $projectSeekingOrganizationsName,
    ]);

    $organizationEngagement = Engagement::factory()->create([
        'recruitment' => 'connector',
        'who' => 'organization',
        'project_id' => $projectSeekingOrganizations->id,
    ]);

    $allProjects = $this->livewire(AllProjects::class, ['seekings' => []]);
    $allProjects->assertSee($projectSeekingParticipantsName);
    $allProjects->assertSee($projectSeekingConnectorsName);
    $allProjects->assertSee($projectSeekingOrganizationsName);

    $allProjects->set('seekings', ['participants']);
    $allProjects->assertSee($projectSeekingParticipantsName);
    $allProjects->assertDontSee($projectSeekingConnectorsName);
    $allProjects->assertDontSee($projectSeekingOrganizationsName);

    $allProjects->set('seekings', ['connectors']);
    $allProjects->assertDontSee($projectSeekingParticipantsName);
    $allProjects->assertSee($projectSeekingConnectorsName);
    $allProjects->assertDontSee($projectSeekingOrganizationsName);

    $allProjects->set('seekings', ['organizations']);
    $allProjects->assertDontSee($projectSeekingParticipantsName);
    $allProjects->assertDontSee($projectSeekingConnectorsName);
    $allProjects->assertSee($projectSeekingOrganizationsName);

    $allProjects->set('seekings', ['participants', 'connectors', 'organizations']);
    $allProjects->assertSee($projectSeekingParticipantsName);
    $allProjects->assertSee($projectSeekingConnectorsName);
    $allProjects->assertSee($projectSeekingOrganizationsName);
});

test('test initiators property change', function () {
    $communityOrganization = Organization::factory()->create();
    $communityOrganizationProjectName = 'Community Organization Project';
    $communityOrganizationProject = Project::factory()->create([
        'projectable_id' => $communityOrganization->id,
        'name->en' => $communityOrganizationProjectName,
        'projectable_type' => 'App\Models\Organization',
    ]);

    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $regulatedOrganizationProjectName = 'Regulated Organization Project';
    $regulatedOrganizationProject = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'name->en' => $regulatedOrganizationProjectName,
        'projectable_type' => 'App\Models\RegulatedOrganization',
    ]);

    $allProjects = $this->livewire(AllProjects::class, ['initiators' => []]);
    $allProjects->assertSee($communityOrganizationProjectName);
    $allProjects->assertSee($regulatedOrganizationProjectName);

    $allProjects->set('initiators', ['organization']);
    $allProjects->assertSee($communityOrganizationProjectName);
    $allProjects->assertDontSee($regulatedOrganizationProjectName);

    $allProjects->set('initiators', ['regulatedOrganization']);
    $allProjects->assertDontSee($communityOrganizationProjectName);
    $allProjects->assertSee($regulatedOrganizationProjectName);

    $allProjects->set('initiators', ['organization', 'regulatedOrganization']);
    $allProjects->assertSee($communityOrganizationProjectName);
    $allProjects->assertSee($regulatedOrganizationProjectName);
});

test('test seekingGroups property change', function () {
    $this->seed(IdentitySeeder::class);

    $disabilityTypeDeaf = Identity::where('name->en', 'Deaf')->first();
    $projectSeekingDeafExperienceName = 'Project Seeking Deaf Experience';
    $projectSeekingDeafExperience = Project::factory()->create([
        'name->en' => $projectSeekingDeafExperienceName,
    ]);
    $engagementSeekingDeafExperience = Engagement::factory()->create(['project_id' => $projectSeekingDeafExperience->id]);
    $matchingStrategySeekingDeafExperience = MatchingStrategy::factory()->create([
        'matchable_type' => 'App\Models\Engagement',
        'matchable_id' => $engagementSeekingDeafExperience->id,
    ]);
    $matchingStrategySeekingDeafExperience->identities()->attach($disabilityTypeDeaf->id);

    $disabilityTypeCognitive = Identity::where('name->en', 'Cognitive disabilities')->first();
    $projectSeekingCognitiveDisabilityExperienceName = 'Project Seeking Cognitive Disability Experience';
    $projectSeekingCognitiveDisabilityExperience = Project::factory()->create([
        'name->en' => $projectSeekingCognitiveDisabilityExperienceName,
    ]);
    $engagementSeekingCognitiveDisabilityExperience = Engagement::factory()->create(['project_id' => $projectSeekingCognitiveDisabilityExperience->id]);
    $matchingStrategySeekingCognitiveDisabilityExperience = MatchingStrategy::factory()->create([
        'matchable_type' => 'App\Models\Engagement',
        'matchable_id' => $engagementSeekingCognitiveDisabilityExperience->id,
    ]);
    $matchingStrategySeekingCognitiveDisabilityExperience->identities()->attach($disabilityTypeCognitive->id);

    $allProjects = $this->livewire(AllProjects::class, ['seekingGroups' => []]);
    $allProjects->assertSee($projectSeekingDeafExperienceName);
    $allProjects->assertSee($projectSeekingCognitiveDisabilityExperienceName);

    $allProjects->set('seekingGroups', [$disabilityTypeDeaf->id]);
    $allProjects->assertSee($projectSeekingDeafExperienceName);
    $allProjects->assertDontSee($projectSeekingCognitiveDisabilityExperienceName);

    $allProjects->set('seekingGroups', [$disabilityTypeCognitive->id]);
    $allProjects->assertDontSee($projectSeekingDeafExperienceName);
    $allProjects->assertSee($projectSeekingCognitiveDisabilityExperienceName);

    $allProjects->set('seekingGroups', [$disabilityTypeDeaf->id, $disabilityTypeCognitive->id]);
    $allProjects->assertSee($projectSeekingDeafExperienceName);
    $allProjects->assertSee($projectSeekingCognitiveDisabilityExperienceName);
});

test('test meetingTypes property change', function () {
    $inpersonInterviewProjectName = 'In person Interview Project';
    $inpersonInterviewProject = Project::factory()->create([
        'name->en' => $inpersonInterviewProjectName,
    ]);
    $inPersonInterviewEngagement = Engagement::factory()->create([
        'project_id' => $inpersonInterviewProject->id,
        'extra_attributes' => ['format' => 'interviews'],
        'meeting_types' => 'in_person',
    ]);

    $virtualWorkshopProjectName = 'Virtual Workshop Project';
    $virtualWorkshopProject = Project::factory()->create([
        'name->en' => $virtualWorkshopProjectName,
    ]);
    $virtualWorkshopEngagement = Engagement::factory()->create([
        'project_id' => $virtualWorkshopProject->id,
        'extra_attributes' => ['format' => 'workshop'],
        'meeting_types' => null,
    ]);
    $virtualWorkshopMeeting = Meeting::factory()->create([
        'engagement_id' => $virtualWorkshopEngagement->id,
        'meeting_types' => 'web_conference',
    ]);

    $phoneFocusGroupProjectName = 'Phone Focus Group Project';
    $phoneFocusGroupProject = Project::factory()->create([
        'name->en' => $phoneFocusGroupProjectName,
    ]);
    $phoneFocusGroupEngagement = Engagement::factory()->create([
        'project_id' => $phoneFocusGroupProject->id,
        'extra_attributes' => ['format' => 'focus-group'],
        'meeting_types' => null,
    ]);
    $phoneFocusGroupMeeting = Meeting::factory()->create([
        'engagement_id' => $phoneFocusGroupEngagement->id,
        'meeting_types' => 'phone',
    ]);

    $allProjects = $this->livewire(AllProjects::class, ['meetingTypes' => []]);
    $allProjects->assertSee($inpersonInterviewProjectName);
    $allProjects->assertSee($virtualWorkshopProjectName);
    $allProjects->assertSee($phoneFocusGroupProjectName);

    $allProjects->set('meetingTypes', ['in_person']);
    $allProjects->assertSee($inpersonInterviewProjectName);
    $allProjects->assertDontSee($virtualWorkshopProjectName);
    $allProjects->assertDontSee($phoneFocusGroupProjectName);

    $allProjects->set('meetingTypes', ['web_conference']);
    $allProjects->assertDontSee($inpersonInterviewProjectName);
    $allProjects->assertSee($virtualWorkshopProjectName);
    $allProjects->assertDontSee($phoneFocusGroupProjectName);

    $allProjects->set('meetingTypes', ['phone']);
    $allProjects->assertDontSee($inpersonInterviewProjectName);
    $allProjects->assertDontSee($virtualWorkshopProjectName);
    $allProjects->assertSee($phoneFocusGroupProjectName);

    $allProjects->set('meetingTypes', ['in_person', 'web_conference', 'phone']);
    $allProjects->assertSee($inpersonInterviewProjectName);
    $allProjects->assertSee($virtualWorkshopProjectName);
    $allProjects->assertSee($phoneFocusGroupProjectName);
});

test('test compensations property change', function () {
    $paidProjectName = 'Paied Project';
    $paidProject = Project::factory()->create([
        'name->en' => $paidProjectName,
    ]);
    $paidEngagement = Engagement::factory()->create(['project_id' => $paidProject->id, 'paid' => true]);

    $volunteerProjectName = 'Volunteer Project';
    $volunteerProject = Project::factory()->create([
        'name->en' => $volunteerProjectName,
    ]);
    $volunteerEngagement = Engagement::factory()->create(['project_id' => $volunteerProject->id, 'paid' => false]);

    $allProjects = $this->livewire(AllProjects::class, ['compensations' => []]);
    $allProjects->assertSee($paidProjectName);
    $allProjects->assertSee($volunteerProjectName);

    $allProjects->set('compensations', ['paid']);
    $allProjects->assertSee($paidProjectName);
    $allProjects->assertDontSee($volunteerProjectName);

    $allProjects->set('compensations', ['volunteer']);
    $allProjects->assertDontSee($paidProjectName);
    $allProjects->assertSee($volunteerProjectName);

    $allProjects->set('compensations', ['paid', 'volunteer']);
    $allProjects->assertSee($paidProjectName);
    $allProjects->assertSee($volunteerProjectName);
});

test('test sectors property change', function () {
    $this->seed(SectorSeeder::class);
    $regulatedPrivateSector = Sector::where('name->en', 'Federally Regulated private sector')->first();
    $privateRegulatedOrganization = RegulatedOrganization::factory()->create();
    $privateRegulatedOrganization->sectors()->save($regulatedPrivateSector);
    $regulatedPrivateProjectName = 'Regulated Private Sector Project';
    $regulatedPrivateProject = Project::factory()->create([
        'name->en' => $regulatedPrivateProjectName,
        'projectable_id' => $privateRegulatedOrganization->id,
    ]);

    $parliamentarySector = Sector::where('name->en', 'Parliamentary entities')->first();
    $parliamentaryOrganization = RegulatedOrganization::factory()->create();
    $parliamentaryOrganization->sectors()->save($parliamentarySector);
    $parliamentaryProjectName = 'Parliamentary Project';
    $parliamentaryProject = Project::factory()->create([
        'name->en' => $parliamentaryProjectName,
        'projectable_id' => $parliamentaryOrganization->id,
    ]);

    $allProjects = $this->livewire(AllProjects::class, ['sectors' => []]);
    $allProjects->assertSee($regulatedPrivateProjectName);
    $allProjects->assertSee($parliamentaryProjectName);

    $allProjects->set('sectors', [$regulatedPrivateSector->id]);
    $allProjects->assertSee($regulatedPrivateProjectName);
    $allProjects->assertDontSee($parliamentaryProjectName);

    $allProjects->set('sectors', [$parliamentarySector->id]);
    $allProjects->assertDontSee($regulatedPrivateProjectName);
    $allProjects->assertSee($parliamentaryProjectName);

    $allProjects->set('sectors', [$regulatedPrivateSector->id, $parliamentarySector->id]);
    $allProjects->assertSee($regulatedPrivateProjectName);
    $allProjects->assertSee($parliamentaryProjectName);
});

test('test impacts property change', function () {
    $this->seed(ImpactSeeder::class);
    $employmentImpact = Impact::where('name->en', 'Employment')->first();
    $employmentImpactProjectName = 'Employment Impact Project';
    $employmentImpactProject = Project::factory()->create([
        'name->en' => $employmentImpactProjectName,
    ]);
    $employmentImpactProject->impacts()->attach($employmentImpact->id);

    $communicationImpact = Impact::where('name->en', 'Communications')->first();
    $communicationImpactProjectName = 'Communication Imapct Project';
    $communicationImpactProject = Project::factory()->create([
        'name->en' => $communicationImpactProjectName,
    ]);
    $communicationImpactProject->impacts()->attach($communicationImpact->id);

    $allProjects = $this->livewire(AllProjects::class, ['impacts' => []]);
    $allProjects->assertSee($employmentImpactProjectName);
    $allProjects->assertSee($communicationImpactProjectName);

    $allProjects->set('impacts', [$employmentImpact->id]);
    $allProjects->assertSee($employmentImpactProjectName);
    $allProjects->assertDontSee($communicationImpactProjectName);

    $allProjects->set('impacts', [$communicationImpact->id]);
    $allProjects->assertDontSee($employmentImpactProjectName);
    $allProjects->assertSee($communicationImpactProjectName);

    $allProjects->set('impacts', [$employmentImpact->id, $communicationImpact->id]);
    $allProjects->assertSee($employmentImpactProjectName);
    $allProjects->assertSee($communicationImpactProjectName);
});

test('test recruitmentMethods property change', function () {
    $openCallProjectName = 'Open Call Project';
    $openCallProject = Project::factory()->create([
        'name->en' => $openCallProjectName,
    ]);
    $openCallEngagement = Engagement::factory()->create(['project_id' => $openCallProject->id, 'recruitment' => 'open-call']);

    $connectorProjectName = 'Connector Project';
    $connectorProject = Project::factory()->create([
        'name->en' => $connectorProjectName,
    ]);
    $connectorEngagement = Engagement::factory()->create(['project_id' => $connectorProject->id, 'recruitment' => 'connector']);

    $allProjects = $this->livewire(AllProjects::class, ['recruitmentMethods' => []]);
    $allProjects->assertSee($openCallProjectName);
    $allProjects->assertSee($connectorProjectName);

    $allProjects->set('recruitmentMethods', ['open-call']);
    $allProjects->assertSee($openCallProjectName);
    $allProjects->assertDontSee($connectorProjectName);

    $allProjects->set('recruitmentMethods', ['connector']);
    $allProjects->assertDontSee($openCallProjectName);
    $allProjects->assertSee($connectorProjectName);

    $allProjects->set('recruitmentMethods', ['open-call', 'connector']);
    $allProjects->assertSee($openCallProjectName);
    $allProjects->assertSee($connectorProjectName);
});

test('test locations property change', function () {
    $regionSpecificProjectName = 'Region Specific Project';
    $regionSpecificProject = Project::factory()->create([
        'name->en' => $regionSpecificProjectName,
    ]);
    $regionSpecificEngagement = Engagement::factory()->create(['project_id' => $regionSpecificProject->id]);
    $regionSpecificMatchingStrategy = $regionSpecificEngagement->matchingStrategy;
    $regionSpecificMatchingStrategy->update([
        'regions' => ['AB'],
    ]);

    $locationSpecificProjectName = 'Location Specific Project';
    $locationSpecificProject = Project::factory()->create([
        'name->en' => $locationSpecificProjectName,
    ]);
    $locationSpecificEngagement = Engagement::factory()->create(['project_id' => $locationSpecificProject->id]);
    $locationSpecificMatchingStrategy = $locationSpecificEngagement->matchingStrategy;
    $locationSpecificMatchingStrategy->update([
        'locations' => [
            ['region' => 'AB', 'locality' => 'Edmonton'],
            ['region' => 'ON', 'locality' => 'Toronto'],
        ],
    ]);

    $allProjects = $this->livewire(AllProjects::class, ['locations' => []]);
    $allProjects->assertSee($regionSpecificProjectName);
    $allProjects->assertSee($locationSpecificProjectName);

    $allProjects->set('locations', ['AB']);
    $allProjects->assertSee($regionSpecificProjectName);
    $allProjects->assertSee($locationSpecificProjectName);

    $allProjects->set('locations', ['ON']);
    $allProjects->assertDontSee($regionSpecificProjectName);
    $allProjects->assertSee($locationSpecificProjectName);

    $allProjects->set('locations', ['AB', 'ON']);
    $allProjects->assertSee($regionSpecificProjectName);
    $allProjects->assertSee($locationSpecificProjectName);
});

test('test selectNone', function () {
    $upComingProjectName = 'Upcoming Project';
    $upComingProject = Project::factory()->create([
        'name->en' => $upComingProjectName,
        'start_date' => Carbon::now()->addDays(5),
    ]);
    $connectorEngagement = Engagement::factory()->create(['project_id' => $upComingProject->id, 'recruitment' => 'connector']);

    $openCallProjectName = 'Open Call Project';
    $openCallProject = Project::factory()->create([
        'name->en' => $openCallProjectName,
        'start_date' => Carbon::now()->subDays(5),
        'end_date' => Carbon::now()->addDays(5),
    ]);
    $openCallEngagement = Engagement::factory()->create(['project_id' => $openCallProject->id, 'recruitment' => 'open-call']);

    $allProjects = $this->livewire(AllProjects::class, ['statuses' => ['upcoming'], 'recruitmentMethods' => ['connector']]);
    $allProjects->assertSee($upComingProjectName);
    $allProjects->assertDontSee($openCallProjectName);

    $allProjects->call('selectNone');
    $allProjects->assertSee($upComingProjectName);
    $allProjects->assertSee($openCallProjectName);
});
