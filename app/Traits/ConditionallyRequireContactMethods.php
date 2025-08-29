<?php

namespace App\Traits;

use App\Enums\ContactMethod;
use App\Enums\ContactPerson;
use Illuminate\Validation\Validator;

trait ConditionallyRequireContactMethods
{
    public function conditionallyRequireContactMethods(Validator $validator)
    {
        $validator->sometimes('email', 'required', function ($input) {
            return $input->preferred_contact_person == ContactPerson::Me->value && $input->preferred_contact_method == ContactMethod::Email->value;
        });

        $validator->sometimes('support_person_email', 'required', function ($input) {
            return $input->preferred_contact_person == ContactPerson::SupportPerson->value && $input->preferred_contact_method == ContactMethod::Email->value;
        });

        $validator->sometimes('phone', 'required', function ($input) {
            return $input->preferred_contact_person == ContactPerson::Me->value && $input->preferred_contact_method == ContactMethod::Phone->value;
        });

        $validator->sometimes('support_person_phone', 'required', function ($input) {
            return $input->preferred_contact_person == ContactPerson::SupportPerson->value && $input->preferred_contact_method == ContactMethod::Phone->value;
        });
    }
}
