<?php

namespace App\View\Components;

use App\Enums\ContactMethod;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContactPoint extends Component
{
    public string $type;

    public string $label;

    public string $value;

    public bool $preferred;

    public bool $vrs;

    public function __construct(string $type, string $value, bool $preferred = false, ?bool $vrs = false)
    {
        $this->type = $type;
        $this->label = $this->type === ContactMethod::Email->value
            ? ContactMethod::labels()[ContactMethod::Email->value]
            : ContactMethod::labels()[ContactMethod::Phone->value];
        $this->value = $this->type === ContactMethod::Email->value ? $value : phone($value, 'CA')->formatForCountry('CA');
        $this->preferred = $preferred;
        $this->vrs = $this->type === ContactMethod::Phone->value && $vrs ? true : false;
    }

    public function render(): View
    {
        return view('components.contact-point');
    }
}
