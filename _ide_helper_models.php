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
	class AccessSupport extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AgeBracket
 *
 * @property int $id
 * @property array $name
 * @property int|null $min
 * @property int|null $max
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Individual[] $communityConnectors
 * @property-read int|null $community_connectors_count
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgeBracket whereUpdatedAt($value)
 */
	class AgeBracket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AreaType
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaType whereUpdatedAt($value)
 */
	class AreaType extends \Eloquent {}
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
	class CommunicationTool extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Constituency
 *
 * @property int $id
 * @property array $name
 * @property array $name_plural
 * @property array $adjective
 * @property array $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Individual[] $communityConnectors
 * @property-read int|null $community_connectors_count
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereAdjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereNamePlural($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Constituency whereUpdatedAt($value)
 */
	class Constituency extends \Eloquent {}
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
	class ConsultingMethod extends \Eloquent {}
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
	class ContentType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Criterion
 *
 * @property int $id
 * @property int $matching_strategy_id
 * @property string $criteriable_type
 * @property int $criteriable_id
 * @property float $weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $criteriable
 * @property-read \App\Models\MatchingStrategy $matchingStrategy
 * @method static \Database\Factories\CriterionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereCriteriableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereCriteriableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereMatchingStrategyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Criterion whereWeight($value)
 */
	class Criterion extends \Eloquent {}
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
	class DefinedTerm extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DisabilityType
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisabilityType whereUpdatedAt($value)
 */
	class DisabilityType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmploymentStatus
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmploymentStatus whereUpdatedAt($value)
 */
	class EmploymentStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Engagement
 *
 * @property array $name
 * @property int $project_id
 * @property string $recruitment
 * @property array $goals
 * @property string|null $timeline
 * @property string|null $meetings
 * @property string|null $reporting
 * @property string|null $other_reporting
 * @property string|null $contacts
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Individual[] $confirmedParticipants
 * @property-read int|null $confirmed_participants_count
 * @property-read \App\Models\MatchingStrategy|null $matchingStrategy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Individual[] $participants
 * @property-read int|null $participants_count
 * @property-read \App\Models\Project $project
 * @method static \Database\Factories\EngagementFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereMeetings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereOtherReporting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereRecruitment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereReporting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereTimeline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Engagement whereUpdatedAt($value)
 */
	class Engagement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EthnoracialIdentity
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity query()
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EthnoracialIdentity whereUpdatedAt($value)
 */
	class EthnoracialIdentity extends \Eloquent {}
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
	class Format extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GenderIdentity
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity query()
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GenderIdentity whereUpdatedAt($value)
 */
	class GenderIdentity extends \Eloquent {}
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
	class Impact extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IndigenousIdentity
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndigenousIdentity whereUpdatedAt($value)
 */
	class IndigenousIdentity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Individual
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $locality
 * @property string|null $region
 * @property int $user_id
 * @property string|null $published_at
 * @property array|null $bio
 * @property array $social_links
 * @property array|null $pronouns
 * @property array|null $picture_alt
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $web_links
 * @property string|null $age_group
 * @property bool $rural_or_remote
 * @property array|null $lived_experience
 * @property array|null $skills_and_strengths
 * @property array $relevant_experiences
 * @property array|null $languages
 * @property array|null $meeting_types
 * @property array|null $working_languages
 * @property array|null $other_lived_experience_connections
 * @property array|null $other_constituency_connections
 * @property string|null $preferred_contact_method
 * @property string|null $preferred_contact_person
 * @property bool|null $vrs
 * @property string $first_language
 * @property string|null $support_person_name
 * @property string|null $support_person_email
 * @property string|null $support_person_phone
 * @property bool|null $support_person_vrs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccessSupport[] $accessSupports
 * @property-read int|null $access_supports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgeBracket[] $ageBracketConnections
 * @property-read int|null $age_bracket_connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $blocks
 * @property-read int|null $blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Constituency[] $constituencies
 * @property-read int|null $constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Constituency[] $constituencyConnections
 * @property-read int|null $constituency_connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Engagement[] $engagements
 * @property-read int|null $engagements_count
 * @property-read string|null $alternate_contact_method
 * @property-read string|null $alternate_contact_point
 * @property-read string $contact_person
 * @property-read string $first_name
 * @property-read string|null $primary_contact_method
 * @property-read string|null $primary_contact_point
 * @property-read bool $requires_vrs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Impact[] $impacts
 * @property-read int|null $impacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IndividualRole[] $individualRoles
 * @property-read int|null $individual_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LivedExperience[] $livedExperienceConnections
 * @property-read int|null $lived_experience_connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LivedExperience[] $livedExperiences
 * @property-read int|null $lived_experiences_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PaymentMethod[] $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projectsOfInterest
 * @property-read int|null $projects_of_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sector[] $sectors
 * @property-read int|null $sectors_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\IndividualFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Individual newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Individual query()
 * @method static \Illuminate\Database\Eloquent\Builder|Individual status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual statusIn($statuses)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereAgeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereFirstLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereLivedExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereMeetingTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereOtherConstituencyConnections($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereOtherLivedExperienceConnections($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePictureAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePreferredContactMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePreferredContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePronouns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereRelevantExperiences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereRuralOrRemote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSkillsAndStrengths($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSocialLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSupportPersonEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSupportPersonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSupportPersonPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSupportPersonVrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereVrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereWebLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereWorkingLanguages($value)
 */
	class Individual extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\IndividualRole
 *
 * @property int $id
 * @property array $name
 * @property array $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRole whereUpdatedAt($value)
 */
	class IndividualRole extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LivedExperience
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Individual[] $communityConnectors
 * @property-read int|null $community_connectors_count
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience query()
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LivedExperience whereUpdatedAt($value)
 */
	class LivedExperience extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MatchingStrategy
 *
 * @property int $id
 * @property string|null $matchable_type
 * @property int|null $matchable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Criterion[] $criteria
 * @property-read int|null $criteria_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $matchable
 * @method static \Database\Factories\MatchingStrategyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy whereMatchableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy whereMatchableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingStrategy whereUpdatedAt($value)
 */
	class MatchingStrategy extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Organization
 *
 * @property int $id
 * @property array $name
 * @property array $slug
 * @property string|null $locality
 * @property string|null $region
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property array|null $languages
 * @property array|null $working_languages
 * @property array|null $about
 * @property array|null $service_areas
 * @property array|null $area_types
 * @property array|null $social_links
 * @property string|null $website_link
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property bool|null $cross_disability
 * @property array|null $other_disability_type
 * @property bool|null $refugees_and_immigrants
 * @property bool|null $trans_people
 * @property bool|null $twoslgbtqia
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $administrators
 * @property-read int|null $administrators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgeBracket[] $ageBrackets
 * @property-read int|null $age_bracket_constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $blocks
 * @property-read int|null $blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $completedProjects
 * @property-read int|null $completed_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisabilityType[] $disabilityTypes
 * @property-read int|null $disability_constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmploymentStatus[] $employmentStatusConstituencies
 * @property-read int|null $employment_status_constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EthnoracialIdentity[] $ethnoracialIdentities
 * @property-read int|null $ethnoracial_constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GenderIdentity[] $genderIdentities
 * @property-read int|null $gender_identity_constituencies_count
 * @property-read int $has_age_brackets
 * @property-read string|null $base_disability_type
 * @property-read int $has_ethnoracial_identities
 * @property-read int $has_gender_and_sexual_identities
 * @property-read int $has_indigenous_identities
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $inProgressProjects
 * @property-read int|null $in_progress_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\IndigenousIdentity[] $indigenousIdentities
 * @property-read int|null $indigenous_constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Hearth\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LivedExperience[] $livedExperiences
 * @property-read int|null $lived_experience_constituencies_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $notificationRecipients
 * @property-read int|null $notification_recipients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrganizationRole[] $organizationRoles
 * @property-read int|null $organization_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $upcomingProjects
 * @property-read int|null $upcoming_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\OrganizationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization statusIn($statuses)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereAreaTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCrossDisability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereOtherDisabilityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereRefugeesAndImmigrants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereServiceAreas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereSocialLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereTransPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereTwoslgbtqia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereWebsiteLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereWorkingLanguages($value)
 */
	class Organization extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrganizationRole
 *
 * @property int $id
 * @property array $name
 * @property array $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationRole whereUpdatedAt($value)
 */
	class OrganizationRole extends \Eloquent {}
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
	class PaymentMethod extends \Eloquent {}
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
	class Phase extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property array|null $goals
 * @property array|null $scope
 * @property array|null $out_of_scope
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $ancestor_id
 * @property array|null $languages
 * @property array|null $outcomes
 * @property bool|null $public_outcomes
 * @property string|null $team_size
 * @property bool|null $team_has_disability_or_deaf_lived_experience
 * @property bool|null $team_has_other_lived_experience
 * @property array|null $team_languages
 * @property array|null $contacts
 * @property bool|null $has_consultant
 * @property string|null $consultant_name
 * @property int|null $consultant_id
 * @property array|null $consultant_responsibilities
 * @property array|null $team_trainings
 * @property string $projectable_type
 * @property int $projectable_id
 * @property-read \App\Models\Individual|null $accessibilityConsultant
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Engagement[] $engagements
 * @property-read int|null $engagements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Impact[] $impacts
 * @property-read int|null $impacts_count
 * @property-read \App\Models\MatchingStrategy|null $matchingStrategy
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $projectable
 * @property-read \App\Models\RegulatedOrganization|null $regulatedOrganization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Engagement[] $upcomingEngagements
 * @property-read int|null $upcoming_engagements_count
 * @method static \Database\Factories\ProjectFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Project statusIn($statuses)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAncestorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereConsultantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereConsultantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereConsultantResponsibilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereHasConsultant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOutOfScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOutcomes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePublicOutcomes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTeamHasDisabilityOrDeafLivedExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTeamHasOtherLivedExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTeamLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTeamSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTeamTrainings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RegulatedOrganization
 *
 * @property int $id
 * @property array $name
 * @property string|null $locality
 * @property string|null $region
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $languages
 * @property array|null $about
 * @property array $accessibility_and_inclusion_links
 * @property array $social_links
 * @property string|null $website_link
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property array $slug
 * @property array|null $service_areas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $administrators
 * @property-read int|null $administrators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $blocks
 * @property-read int|null $blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $completedProjects
 * @property-read int|null $completed_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $inProgressProjects
 * @property-read int|null $in_progress_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Hearth\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $notificationRecipients
 * @property-read int|null $notification_recipients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sector[] $sectors
 * @property-read int|null $sectors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $upcomingProjects
 * @property-read int|null $upcoming_projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RegulatedOrganizationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization statusIn($statuses)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereAccessibilityAndInclusionLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereServiceAreas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereSocialLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegulatedOrganization whereWebsiteLink($value)
 */
	class RegulatedOrganization extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Resource
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $content_type_id
 * @property array $title
 * @property array $slug
 * @property array $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ContentType|null $contentType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Format[] $formats
 * @property-read int|null $formats_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Format[] $originalFormat
 * @property-read int|null $original_format_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Phase[] $phases
 * @property-read int|null $phases_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceCollection[] $resourceCollections
 * @property-read int|null $resource_collections_count
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
	class Resource extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ResourceCollection
 *
 * @property int $id
 * @property int $user_id
 * @property array $title
 * @property array $slug
 * @property array $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property-read int|null $resources_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ResourceCollectionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResourceCollection whereUserId($value)
 */
	class ResourceCollection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Sector
 *
 * @property int $id
 * @property array $name
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereUpdatedAt($value)
 */
	class Sector extends \Eloquent {}
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
	class Topic extends \Eloquent {}
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
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $locale
 * @property string $theme
 * @property string $context
 * @property string|null $signed_language
 * @property bool|null $finished_introduction
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Individual[] $blockedIndividuals
 * @property-read int|null $blocked_individuals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $blockedOrganizations
 * @property-read int|null $blocked_organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegulatedOrganization[] $blockedRegulatedOrganizations
 * @property-read int|null $blocked_regulated_organizations_count
 * @property-read mixed $organization
 * @property-read mixed $regulated_organization
 * @property-read \App\Models\Individual|null $individual
 * @property-read \Illuminate\Database\Eloquent\Collection|\Hearth\Models\Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $organizations
 * @property-read int|null $organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization[] $organizationsForNotification
 * @property-read int|null $organizations_for_notification_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegulatedOrganization[] $regulatedOrganizations
 * @property-read int|null $regulated_organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RegulatedOrganization[] $regulatedOrganizationsForNotification
 * @property-read int|null $regulated_organizations_for_notification_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResourceCollection[] $resourceCollections
 * @property-read int|null $resource_collections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property-read int|null $resources_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFinishedIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSignedLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Translation\HasLocalePreference, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

