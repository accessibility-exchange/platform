<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Str;

class UniqueUserEmail implements InvokableRule
{
    public $id;

    public $idColumn;

    // Can use the $id and $idColumn to define a model to ignore. Similar to how Laravel's unique validation rule works
    // see: https://laravel.com/docs/9.x/validation#rule-unique
    public function __construct(mixed $id = null, string $idColumn = null)
    {
        $this->id = $id;
        $this->idColumn = $idColumn ?? 'id';
    }

    public function __invoke($attribute, mixed $value, $fail): void
    {
        $exists = User::whereBlind($attribute, $attribute.'_index', Str::lower($value))
            ->when($this->id, function ($query, $role) {
                $query->whereNot(function ($query) {
                    $query->where($this->idColumn, $this->id);
                });
            })
            ->exists();
        if ($exists) {
            $fail(__('A user with this email already exists.'));
        }
    }
}
