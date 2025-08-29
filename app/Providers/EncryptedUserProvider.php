<?php

namespace App\Providers;

use Closure;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EncryptedUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials): (Authenticatable&Model)|null
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return null;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if ($key === 'email') {
                $query->whereBlind('email', 'email_index', Str::lower($value));
            } elseif (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } elseif ($value instanceof Closure) {
                $value($query);
            } else {
                $query->where($key, $value);
            }
        }

        /** @var Authenticatable&Model */
        return $query->first();
    }
}
