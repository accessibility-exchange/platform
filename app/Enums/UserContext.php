<?php

namespace App\Enums;

enum UserContext: string
{
    case Administrator = 'administrator';
    case Individual = 'individual';
    case Organization = 'organization';
    case RegulatedOrganization = 'regulated-organization';
    case TrainingParticipant = 'training-participant';

    public static function labels(): array
    {
        return [
            'individual' => __('Individual'),
            'organization' => __('Community Organization'),
            'regulated-organization' => __('Federally Regulated Organization'),
            'training-participant' => __('Training Participant'),
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::Individual => __('I am a person with a disability, a Deaf person, or am a family member or supporter.'),
            self::Organization => __('I am with a community organization that represents or serves the disability community, the Deaf community, or another kind of civil society organization that is concerned about accessibility issues.'),
            self::RegulatedOrganization => __('I work for a private business, the federal government, or a public sector organization regulated under the Accessible Canada Act.'),
            self::TrainingParticipant => __('I am seeking training assigned by my organization or business.'),
            default => null,
        };
    }

    public function interpretation(): array
    {
        return match ($this) {
            self::Individual => ['name' => __('Individual', [], 'en'), 'namespace' => 'user_context'],
            self::Organization => ['name' => __('Community Organization', [], 'en'), 'namespace' => 'user_context'],
            self::RegulatedOrganization => ['name' => __('Federally Regulated Organization', [], 'en'), 'namespace' => 'user_context'],
            self::TrainingParticipant => ['name' => __('Training Participant', [], 'en'), 'namespace' => 'user_context'],
            default => null,
        };
    }
}
