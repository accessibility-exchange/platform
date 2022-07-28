<?php

namespace App\Traits;

trait HasContactPerson
{
    public function getContactMethodsAttribute(): array
    {
        $methods = [];

        if (! empty($this->contact_person_email)) {
            $methods[] = 'email';
        }
        if (! empty($this->contact_person_phone)) {
            $methods[] = 'phone';
        }

        return $methods;
    }

    public function getPrimaryContactPointAttribute(): string
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'phone' => $this->contact_person_phone->formatForCountry('CA'),
            default => $this->contact_person_email,
        };

        if ($this->preferred_contact_method === 'phone' && $this->contact_person_vrs) {
            $contactPoint .= ".  \n".__(':contact_person requires VRS for phone calls', ['contact_person' => $this->contact_person_name]);
        }

        return $contactPoint;
    }

    public function getPrimaryContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'phone' => __('Call :contact_person at :phone_number.', [
                'contact_person' => $this->contact_person_name,
                'phone_number' => $this->primary_contact_point,
            ]),
            default => __('Send an email to :contact_person at :email.', [
                'contact_person' => $this->contact_person_name,
                'email' => '<'.$this->primary_contact_point.'>',
            ])
        };
    }

    public function getAlternateContactPointAttribute(): string|null
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'phone' => $this->contact_person_email,
            default => $this->contact_person_phone?->formatForCountry('CA'),
        };

        if ($this->preferred_contact_method === 'email' && $this->contact_person_vrs) {
            $contactPoint .= ".  \n".__(':contact_person requires VRS for phone calls', ['contact_person' => $this->contact_person_name]);
        }

        return $contactPoint;
    }

    public function getAlternateContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'phone' => $this->alternate_contact_point ? '<'.$this->alternate_contact_point.'>' : null,
            default => $this->alternate_contact_point ?? null
        };
    }
}
