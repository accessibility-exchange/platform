<?php

namespace App\Traits;

use Illuminate\Validation\Validator;

trait ConditionallyRequireContactMethods
{
    public function conditionallyRequireContactMethods(Validator $validator)
    {
        $validator->sometimes('email', 'required', function ($input) {
            return $input->preferred_contact_person == 'me' && $input->preferred_contact_method == 'email';
        });

        $validator->sometimes('support_person_email', 'required', function ($input) {
            return $input->preferred_contact_person == 'support-person' && $input->preferred_contact_method == 'email';
        });

        $validator->sometimes('phone', 'required', function ($input) {
            return $input->preferred_contact_person == 'me' && $input->preferred_contact_method == 'phone';
        });

        $validator->sometimes('support_person_phone', 'required', function ($input) {
            return $input->preferred_contact_person == 'support-person' && $input->preferred_contact_method == 'phone';
        });
    }
}
