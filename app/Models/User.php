<?php

namespace App\Models;

use App\Traits\HasSchemalessAttributes;
use Hearth\Models\Membership;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\EncryptedRow;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use TheIconic\NameParser\Parser as NameParser;

class User extends Authenticatable implements CipherSweetEncrypted, HasLocalePreference, MustVerifyEmail
{
    use CascadesDeletes;
    use HasFactory;
    use HasSchemalessAttributes;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use UsesCipherSweet;

    protected $attributes = [
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'me',
        'preferred_notification_method' => 'email',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'signed_language',
        'context',
        'finished_introduction',
        'theme',
        'text_to_speech',
        'sign_language_translations',
        'phone',
        'vrs',
        'support_person_name',
        'support_person_phone',
        'support_person_email',
        'support_person_vrs',
        'preferred_contact_method',
        'preferred_contact_person',
        'preferred_notification_method',
        'notification_settings',
        'extra_attributes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'finished_introduction' => 'boolean',
        'text_to_speech' => 'boolean',
        'phone' => E164PhoneNumberCast::class.':CA',
        'vrs' => 'boolean',
        'support_person_phone' => E164PhoneNumberCast::class.':CA',
        'support_person_vrs' => 'boolean',
        'notification_settings' => SchemalessAttributes::class,
        'extra_attributes' => SchemalessAttributes::class,
    ];

    protected mixed $cascadeDeletes = [
        'organizations',
        'regulatedOrganizations',
    ];

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('name')
            ->addField('phone')
            ->addField('email')
            ->addBlindIndex('email', new BlindIndex('email_index'))
            ->addField('support_person_name')
            ->addField('support_person_phone')
            ->addField('support_person_email');
    }

    public function preferredLocale()
    {
        return $this->locale;
    }

    public function invitation(): Invitation
    {
        return Invitation::where('email', $this->email)->firstOrFail();
    }

    public function introduction(): string
    {
        return match ($this->context) {
            'individual' => __('Video for individuals.'),
            'organization' => __('Video for community organizations.'),
            'regulated-organization' => __('Video for regulated organizations.'),
            'regulated-organization-employee' => __('Video for regulated organization employees.'),
            default => '',
        };
    }

    public function getFirstNameAttribute(): string
    {
        return (new NameParser())->parse($this->attributes['name'])->getFirstname();
    }

    public function getContactPersonAttribute(): string
    {
        return $this->preferred_contact_person === 'me' ? $this->first_name : $this->support_person_name;
    }

    public function getContactMethodsAttribute(): array
    {
        $methods = [];

        if ($this->preferred_contact_person == 'me') {
            if (! empty($this->email)) {
                $methods[] = 'email';
            }
            if (! empty($this->phone)) {
                $methods[] = 'phone';
            }
        } elseif ($this->preferred_contact_person == 'support-person') {
            if (! empty($this->support_person_email)) {
                $methods[] = 'email';
            }
            if (! empty($this->support_person_phone)) {
                $methods[] = 'phone';
            }
        }

        return $methods;
    }

    public function getPrimaryContactPointAttribute(): string|null
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'email' => $this->preferred_contact_person === 'me' ?
                $this->email :
                $this->support_person_email,
            'phone' => $this->preferred_contact_person === 'me' ?
                $this->phone->formatForCountry('CA') :
                $this->support_person_phone->formatForCountry('CA'),
            default => null,
        };

        if ($this->preferred_contact_method === 'phone' && $this->requires_vrs) {
            $contactPoint .= ".  \n".__(':contact_person requires VRS for phone calls', ['contact_person' => $this->contact_person]);
        }

        return $contactPoint;
    }

    public function getRequiresVrsAttribute(): null|bool
    {
        return $this->preferred_contact_person === 'me' ?
            $this->vrs :
            $this->support_person_vrs;
    }

    public function getPrimaryContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'email' => __('Send an email to :contact_qualifier:contact_person at :email.', [
                'contact_qualifier' => $this->preferred_contact_person == 'me' ? '' : __(':name’s support person, ', ['name' => $this->first_name]),
                'contact_person' => $this->preferred_contact_person == 'me' ? $this->contact_person : $this->contact_person.',',
                'email' => '<'.$this->primary_contact_point.'>',
            ]),
            'phone' => __('Call :contact_qualifier:contact_person at :phone_number.', [
                'contact_qualifier' => $this->preferred_contact_person == 'me' ? '' : __(':name’s support person, ', ['name' => $this->first_name]),
                'contact_person' => $this->preferred_contact_person == 'me' ? $this->contact_person : $this->contact_person.',',
                'phone_number' => $this->primary_contact_point,
            ]),
            default => null
        };
    }

    public function getAlternateContactPointAttribute(): string|null
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'email' => $this->preferred_contact_person === 'me' ?
                $this->phone?->formatForCountry('CA') :
                $this->support_person_phone?->formatForCountry('CA'),
            'phone' => $this->preferred_contact_person === 'me' ?
                $this->email ?? null :
                $this->support_person_email ?? null,
            default => null,
        };

        if ($this->preferred_contact_method === 'email' && $this->requires_vrs) {
            $contactPoint .= "  \n".__(':contact_person requires VRS for phone calls.', ['contact_person' => $this->contact_person]);
        }

        return $contactPoint;
    }

    public function getAlternateContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'email' => $this->alternate_contact_point ?? null,
            'phone' => $this->alternate_contact_point ? '<'.$this->alternate_contact_point.'>' : null,
            default => null
        };
    }

    public function individual(): HasOne
    {
        return $this->hasOne(Individual::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function resourceCollections(): HasMany
    {
        return $this->hasMany(ResourceCollection::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function organizations(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'membershipable', 'memberships')
            ->using(Membership::class)
            ->as('membership')
            ->withPivot(['role', 'id'])
            ->withTimestamps();
    }

    public function regulatedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'membershipable', 'memberships')
            ->using(Membership::class)
            ->as('membership')
            ->withPivot(['role', 'id'])
            ->withTimestamps();
    }

    public function getOrganizationAttribute(): mixed
    {
        return $this->organizations->first();
    }

    public function getRegulatedOrganizationAttribute(): mixed
    {
        return $this->regulatedOrganizations->first();
    }

    public function projectable(): Organization|RegulatedOrganization|null
    {
        if ($this->context === 'organization') {
            return $this->organization;
        }

        if ($this->context === 'regulated-organization') {
            return $this->regulatedOrganization;
        }

        return null;
    }

    public function projects(): Collection
    {
        if ($this->projectable()->projects->isNotEmpty()) {
            return $this->projectable()->projects;
        }

        return new Collection([]);
    }

    public function isMemberOf(mixed $model): bool
    {
        return $model->hasUserWithEmail($this->email);
    }

    public function isAdministratorOf(mixed $model): bool
    {
        return $model->hasAdministratorWithEmail($this->email);
    }

    public function blockedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'blockable')->orderBy('name');
    }

    public function blockedRegulatedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'blockable')->orderBy('name');
    }

    public function blockedIndividuals(): MorphToMany
    {
        return $this->morphedByMany(Individual::class, 'blockable')->orderBy('name');
    }

    public function organizationsForNotification(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'notificationable')->orderBy('name');
    }

    public function regulatedOrganizationsForNotification(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'notificationable')->orderBy('name');
    }

    public function isReceivingNotificationsFor(RegulatedOrganization|Organization $notificationable): bool
    {
        return $notificationable->isNotifying($this);
    }

    /**
     * Is two-factor authentication enabled for this user?
     *
     * @return bool
     */
    public function twoFactorAuthEnabled(): bool
    {
        return ! is_null($this->two_factor_secret);
    }
}
