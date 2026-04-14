<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ideal and Minimum Participants Floor
    |--------------------------------------------------------------------------
    |
    | The minimum allowed value for both ideal and minimum participant counts
    | when creating or updating an engagement.
    |
    */

    'ideal_participants_floor' => env('ENGAGEMENT_IDEAL_PARTICIPANTS_FLOOR', 1),
    'minimum_participants_floor' => env('ENGAGEMENT_MINIMUM_PARTICIPANTS_FLOOR', 1),

];
