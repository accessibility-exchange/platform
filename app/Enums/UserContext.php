<?php

namespace App\Enums;

enum UserContext: string
{
    case Administrator = 'administrator';
    case Individual = 'individual';
    case Organization = 'organization';
    case RegulatedOrganization = 'regulated-organization';
    case Employee = 'employee';

    public static function labels(): array
    {
        return [
            'individual' => __('Individual'),
            'organization' => __('Community Organization'),
            'regulated-organization' => __('Regulated Organizations: Business, Federal Government and Public Sector Organizations'),
            'employee' => __('Employee Seeking Training'),
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::Individual => __('I am someone who has lived experience of being disabled or Deaf, or I am a family member or supporter of a person who is disabled or Deaf.'),
            self::Organization => __('I am a part of a community organization that represents or serves the disability community, the Deaf community. Or, I am a part of a civil society.'),
            self::RegulatedOrganization => __('I work for a business, the federal government or a public sector organization regulated under the Accessible Canada Act.'),
            self::Employee => __('I am an employee seeking training assigned by my organization or business.'),
            default => null,
        };
    }
}
