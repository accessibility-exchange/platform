<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Propaganistas\LaravelPhone\PhoneNumber;

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
        $this->label = $this->type === 'email' ? __('Email') : __('Phone');
        $this->value = $this->type === 'email' ? $value : PhoneNumber::make($value, 'CA')->formatForCountry('CA');
        $this->preferred = $preferred;
        $this->vrs = $this->type === 'phone' && $vrs ? true : false;
    }

    public function render(): View
    {
        return view('components.contact-point');
    }
}
