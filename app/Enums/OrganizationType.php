<?php

namespace App\Enums;

enum OrganizationType: string
{
    case Representative = 'representative';
    case Support = 'support';
    case CivilSociety = 'civil-society';

    public static function labels(): array
    {
        return [
            'representative' => __('Representative organization'),
            'support' => __('Support organization'),
            'civil-society' => __('Civil society organization'),
        ];
    }

    public static function pluralLabels(): array
    {
        return [
            'representative' => __('Representative organizations'),
            'support' => __('Support organizations'),
            'civil-society' => __('Civil society organizations'),
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::Representative => __('Organizations “of” disability, Deaf, and family-based organizations. Constituted primarily by people with disabilities.'),
            self::Support => __('Organizations that provide support “for” disability, Deaf, and family-based members. Not constituted primarily by people with disabilities.'),
            self::CivilSociety => __('Organizations which have some constituency of persons with disabilities, Deaf persons, or family members, but these groups are not their primary mandate. Groups served, for example, can include: Indigenous organizations, 2SLGBTQ+ organizations, immigrant and refugee groups, and women’s groups.'),
        };
    }
}
