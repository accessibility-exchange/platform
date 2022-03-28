<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AccessSupport
 *
 * @property int $id
 * @property array $name
 * @property bool|null $in_person
 * @property bool|null $virtual
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport whereInPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessSupport whereVirtual($value)
 */
    class AccessSupport extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Collection
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Resource[] $resources
 * @property-read \Illuminate\Database\Eloquent\Collection|\Story[] $stories
 * @property int $id
 * @property int|null $user_id
 * @property array $title
 * @property array $description
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $resources_count
 * @property-read int|null $stories_count
 * @method static \Database\Factories\CollectionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUserId($value)
 */
    class Collection extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\CommunicationTool
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunicationTool whereUpdatedAt($value)
 */
    class CommunicationTool extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Community
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Community newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Community newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Community query()
 * @method static \Illuminate\Database\Eloquent\Builder|Community whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Community whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Community whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Community whereUpdatedAt($value)
 */
    class Community extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\CommunityMember
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $locality
 * @property string|null $region
 * @property int $user_id
 * @property string|null $published_at
 * @property array|null $bio
 * @property array $links
 * @property array|null $pronouns
 * @property array|null $picture_alt
 * @property string $creator
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $roles
 * @property bool $hide_location
 * @property array|null $other_links
 * @property array|null $areas_of_interest
 * @property array|null $service_preference
 * @property string|null $age_group
 * @property bool $rural_or_remote
 * @property array|null $other_lived_experience
 * @property array|null $lived_experience
 * @property array|null $skills_and_strengths
 * @property array|null $work_and_volunteer_experiences
 * @property array|null $languages
 * @property array|null $support_people
 * @property array|null $preferred_contact_methods
 * @property array|null $meeting_types
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccessSupport[] $accessSupports
 * @property-read int|null $access_supports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Community[] $communities
 * @property-read int|null $communities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $currentProjects
 * @property-read int|null $current_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entity[] $entities
 * @property-read int|null $entities_count
 * @property-read string $phone_number
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Impact[] $impacts
 * @property-read int|null $impacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LivedExperience[] $livedExperiences
 * @property-read int|null $lived_experiences_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $pastProjects
 * @property-read int|null $past_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PaymentMethod[] $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projectsOfInterest
 * @property-read int|null $projects_of_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sector[] $sectors
 * @property-read int|null $sectors_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CommunityMemberFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember statusIn($statuses)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereAgeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereAreasOfInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereHideLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereLivedExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereMeetingTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereOtherLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereOtherLivedExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember wherePictureAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember wherePreferredContactMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember wherePronouns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereRuralOrRemote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereServicePreference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereSkillsAndStrengths($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereSupportPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommunityMember whereWorkAndVolunteerExperiences($value)
 */
    class CommunityMember extends \Eloquent implements \Spatie\MediaLibrary\HasMedia
    {
    }
}

namespace App\Models{
/**
 * App\Models\ConsultingMethod
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsultingMethod whereUpdatedAt($value)
 */
    class ConsultingMethod extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\ContentType
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property-read int|null $resources_count
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentType whereUpdatedAt($value)
 */
    class ContentType extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\DefinedTerm
 *
 * @property int $id
 * @property array $term
 * @property array $definition
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DefinedTermFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm query()
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefinedTerm whereUpdatedAt($value)
 */
    class DefinedTerm extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Engagement
 *
 * @property array $name
 * @property int $project_id
 * @property string $recruitment
 * @property array $goals
 * @property mixed|null $timeline
 * @property mixed|null $events
 * @property mixed|null $reporting
 * @property mixed|null $contacts
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @method static \Database\Factories\EngagementFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereRecruitment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereReporting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereTimeline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereUpdatedAt($value)
 */
    class Engagement extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Entity
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $locality
 * @property string $region
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $administrators
 * @property-read int|null $administrators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $communityMembers
 * @property-read int|null $community_members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $currentProjects
 * @property-read int|null $current_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $futureProjects
 * @property-read int|null $future_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $pastProjects
 * @property-read int|null $past_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sector[] $sectors
 * @property-read int|null $sectors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\EntityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereUpdatedAt($value)
 */
    class Entity extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Format
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Format newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Format newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Format query()
 * @method static \Illuminate\Database\Eloquent\Builder|Format whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Format whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Format whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Format whereUpdatedAt($value)
 */
    class Format extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Impact
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Impact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Impact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Impact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Impact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impact whereUpdatedAt($value)
 */
    class Impact extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property string $inviteable_type
 * @property int $inviteable_id
 * @property string $email
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $inviteable
 * @method static \Database\Factories\InvitationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereInviteableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereInviteableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
 */
    class Invitation extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\LivedExperience
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience query()
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereUpdatedAt($value)
 */
    class LivedExperience extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Membership
 *
 * @property int $id
 * @property int $user_id
 * @property string $membership_type
 * @property int $membership_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereMembershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserId($value)
 */
    class Membership extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Organization
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $locality
 * @property string $region
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $administrators
 * @property-read int|null $administrators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\OrganizationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 */
    class Organization extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\PaymentMethod
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 */
    class PaymentMethod extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Phase
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Phase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phase query()
 * @method static \Illuminate\Database\Eloquent\Builder|Phase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phase whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phase whereUpdatedAt($value)
 */
    class Phase extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property array $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int $entity_id
 * @property array|null $regions
 * @property array|null $goals
 * @property array|null $impact
 * @property array|null $out_of_scope
 * @property bool|null $virtual_consultation
 * @property array|null $timeline
 * @property array|null $payment_terms
 * @property bool|null $payment_negotiable
 * @property bool|null $existing_clients
 * @property bool|null $prospective_clients
 * @property bool|null $employees
 * @property array|null $priority_outreach
 * @property array|null $locality
 * @property array|null $location_description
 * @property int|null $min
 * @property int|null $max
 * @property array|null $anything_else
 * @property bool|null $flexible_deadlines
 * @property bool|null $flexible_breaks
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property bool|null $found_participants
 * @property bool|null $confirmed_participants
 * @property bool|null $scheduled_planning_meeting
 * @property bool|null $notified_of_planning_meeting
 * @property bool|null $prepared_project_orientation
 * @property bool|null $prepared_contractual_documents
 * @property bool|null $booked_access_services_for_planning
 * @property bool|null $finished_planning_meeting
 * @property bool|null $scheduled_consultation_meetings
 * @property bool|null $notified_of_consultation_meetings
 * @property bool|null $prepared_consultation_materials
 * @property bool|null $booked_access_services_for_consultations
 * @property bool|null $finished_consultation_meetings
 * @property bool|null $prepared_accessibility_plan
 * @property bool|null $prepared_follow_up_plan
 * @property bool|null $shared_plans_with_participants
 * @property int|null $published_accessibility_plan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccessSupport[] $accessSupports
 * @property-read int|null $access_supports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunicationTool[] $communicationTools
 * @property-read int|null $communication_tools_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Community[] $communities
 * @property-read int|null $communities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $confirmedParticipants
 * @property-read int|null $confirmed_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConsultingMethod[] $consultingMethods
 * @property-read int|null $consulting_methods_count
 * @property-read \App\Models\Entity $entity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $exitedParticipants
 * @property-read int|null $exited_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Impact[] $impacts
 * @property-read int|null $impacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $interestedCommunityMembers
 * @property-read int|null $interested_community_members_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $participants
 * @property-read int|null $participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PaymentMethod[] $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $requestedParticipants
 * @property-read int|null $requested_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommunityMember[] $shortlistedParticipants
 * @property-read int|null $shortlisted_participants_count
 * @method static \Database\Factories\ProjectFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Project statusIn($statuses)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAnythingElse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereBookedAccessServicesForConsultations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereBookedAccessServicesForPlanning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereConfirmedParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereExistingClients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFinishedConsultationMeetings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFinishedPlanningMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFlexibleBreaks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFlexibleDeadlines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFoundParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLocationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereNotifiedOfConsultationMeetings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereNotifiedOfPlanningMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOutOfScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePaymentNegotiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreparedAccessibilityPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreparedConsultationMaterials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreparedContractualDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreparedFollowUpPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePreparedProjectOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePriorityOutreach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProspectiveClients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePublishedAccessibilityPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereRegions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereScheduledConsultationMeetings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereScheduledPlanningMeeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSharedPlansWithParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTimeline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereVirtualConsultation($value)
 */
    class Project extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Resource
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $content_type_id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Collection[] $collections
 * @property-read int|null $collections_count
 * @property-read \App\Models\ContentType|null $contentType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Format[] $formats
 * @property-read int|null $formats_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Format[] $originalFormat
 * @property-read int|null $original_format_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Phase[] $phases
 * @property-read int|null $phases_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Topic[] $topics
 * @property-read int|null $topics_count
 * @method static \Database\Factories\ResourceFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereContentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereUserId($value)
 */
    class Resource extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $community_member_id
 * @property int $project_id
 * @property string|null $body
 * @property int $met_access_needs
 * @property int $open_to_feedback
 * @property int $kind_and_patient
 * @property int $valued_input
 * @property int $respectful_of_identity
 * @property int $sensitive_to_comfort_levels
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CommunityMember $communityMember
 * @property-read \App\Models\Project $project
 * @method static \Database\Factories\ReviewFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCommunityMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereKindAndPatient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereMetAccessNeeds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereOpenToFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRespectfulOfIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereSensitiveToComfortLevels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereValuedInput($value)
 */
    class Review extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Sector
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereUpdatedAt($value)
 */
    class Sector extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Story
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Collection[] $collections
 * @property-read int|null $collections_count
 * @method static \Database\Factories\StoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Story newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Story newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Story query()
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUserId($value)
 */
    class Story extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\Topic
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedAt($value)
 */
    class Topic extends \Eloquent
    {
    }
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string $locale
 * @property string $theme
 * @property string $context
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CommunityMember|null $communityMember
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entity[] $entities
 * @property-read int|null $entities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $organizations
 * @property-read int|null $organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Story[] $stories
 * @property-read int|null $stories_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
    class User extends \Eloquent implements \Illuminate\Contracts\Translation\HasLocalePreference, \Illuminate\Contracts\Auth\MustVerifyEmail
    {
    }
}
