<?php

namespace App\Enums;

enum ConsultationPhase: string
{
    case Design = 'design';
    case Engage = 'engage';
    case DeepenUnderstanding = 'deepen-understanding';

    public static function labels(): array
    {
        return [
            'design' => __('Design'),
            'engage' => __('Engage'),
            'deepen-understanding' => __('Deepen understanding'),
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::Design => __('Design your inclusive and accessible consultation'),
            self::Engage => __('Engage with disability and Deaf communities and hold meaningful consultations'),
            self::DeepenUnderstanding => __('')
        };
    }
}
