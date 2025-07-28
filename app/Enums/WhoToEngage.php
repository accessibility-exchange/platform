<?php

namespace App\Enums;

enum WhoToEngage: string
{
    case Individuals = 'individuals';
    case Organization = 'organization';

    public static function labels(): array
    {
        return [
            'individuals' => __('Individuals with lived experience of being disabled or Deaf'),
            'organization' => __('A community organization who represents or supports the disability or Deaf community'),
        ];
    }

    // Must be called with safe_inlineMarkdown() or safe_markdown to render correctly
    public function markdownLabel(): string
    {
        return match ($this) {
            self::Individuals => __('**Individuals** with lived experience of being disabled or Deaf'),
            self::Organization => __('**A community organization** who represents or supports the disability or Deaf community'),
        };
    }
}
