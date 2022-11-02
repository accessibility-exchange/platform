<?php

namespace App\Models;

use Hearth\Models\Membership;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Makeable\EloquentStatus\HasStatus;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\EncryptedRow;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use TheIconic\NameParser\Parser as NameParser;

/**
 * @property Collection $unreadNotifications
 */
class User extends Authenticatable implements CipherSweetEncrypted, HasLocalePreference, MustVerifyEmail
{
    use CascadesDeletes;
    use HasFactory;
    use HasStatus;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use UsesCipherSweet;
    use SchemalessAttributesTrait;
    use HasMergedRelationships;

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
        'accepted_terms_of_service_at',
        'accepted_privacy_policy_at',
        'oriented_at',
        'suspended_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'accepted_terms_of_service_at' => 'datetime',
        'accepted_privacy_policy_at' => 'datetime',
        'oriented_at' => 'datetime',
        'suspended_at' => 'datetime',
        'finished_introduction' => 'boolean',
        'text_to_speech' => 'boolean',
        'phone' => E164PhoneNumberCast::class.':CA',
        'vrs' => 'boolean',
        'support_person_phone' => E164PhoneNumberCast::class.':CA',
        'support_person_vrs' => 'boolean',
    ];

    protected array $schemalessAttributes = [
        'extra_attributes',
        'notification_settings',
    ];

    protected mixed $cascadeDeletes = [
        'organizations',
        'regulatedOrganizations',
    ];

    public function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    public function routeNotificationForMail(Notification $notification): array
    {
        return match ($this->preferred_contact_person) {
            'support-person' => [$this->support_person_email => $this->support_person_name],
            default => [$this->email => $this->name]
        };
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        return match ($this->preferred_contact_person) {
            'support-person' => $this->support_person_phone,
            default => $this->phone
        };
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extra_attributes->modelScope();
    }

    public function scopeWithNotificationSettings(): Builder
    {
        return $this->notification_settings->modelScope();
    }

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

    public function teamInvitation(): Invitation|null
    {
        return Invitation::where('email', $this->email)->whereIn('invitationable_type', ['App\Models\Organization', 'App\Models\RegulatedOrganization'])->first() ?? null;
    }

    public function participantInvitations(): Collection
    {
        return Invitation::where([
            ['email', $this->email],
            ['role', 'participant'],
        ])->get();
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
            $methods[] = 'email';
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
            'phone' => $this->preferred_contact_person === 'me' ?
                $this->phone->formatForCountry('CA') :
                $this->support_person_phone->formatForCountry('CA'),
            default => $this->preferred_contact_person === 'me' ?
                $this->email :
                $this->support_person_email,
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
            'phone' => __('Call :contact_qualifier:contact_person at :phone_number.', [
                'contact_qualifier' => $this->preferred_contact_person == 'me' ? '' : __(':name’s support person, ', ['name' => $this->first_name]),
                'contact_person' => $this->preferred_contact_person == 'me' ? $this->contact_person : $this->contact_person.',',
                'phone_number' => $this->primary_contact_point,
            ]),
            default => __('Send an email to :contact_qualifier:contact_person at :email.', [
                'contact_qualifier' => $this->preferred_contact_person == 'me' ? '' : __(':name’s support person, ', ['name' => $this->first_name]),
                'contact_person' => $this->preferred_contact_person == 'me' ? $this->contact_person : $this->contact_person.',',
                'email' => '<'.$this->primary_contact_point.'>',
            ])
        };
    }

    public function getAlternateContactPointAttribute(): string|null
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'phone' => $this->preferred_contact_person === 'me' ?
                $this->email ?? null :
                $this->support_person_email ?? null,
            default => $this->preferred_contact_person === 'me' ?
                $this->phone?->formatForCountry('CA') :
                $this->support_person_phone?->formatForCountry('CA'),
        };

        if ($this->preferred_contact_method === 'email' && $this->requires_vrs) {
            $contactPoint .= "  \n".__(':contact_person requires VRS for phone calls.', ['contact_person' => $this->contact_person]);
        }

        return $contactPoint;
    }

    public function getAlternateContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'phone' => $this->alternate_contact_point ? '<'.$this->alternate_contact_point.'>' : null,
            default => $this->alternate_contact_point ?? null,
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

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)
            ->withPivot('started_at', 'finished_at', 'received_certificate_at')
            ->withTimestamps();
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)
            ->withPivot('started_content_at', 'finished_content_at', 'completed_at')
            ->withTimestamps();
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)
            ->withPivot('attempts', 'score')
            ->withTimestamps();
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

    public function getProjectableAttribute(): Organization|RegulatedOrganization|null
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
        if ($this->projectable->projects->isNotEmpty()) {
            return $this->projectable->projects;
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

    public function isOnlyAdministratorOfOrganization(): bool
    {
        if (count($this->organizations) > 0) {
            if ($this->organization->administrators()->count() === 1 && $this->isAdministratorOf($this->organization)) {
                return true;
            }
        }

        return false;
    }

    public function isOnlyAdministratorOfRegulatedOrganization(): bool
    {
        if (count($this->regulatedOrganizations) > 0) {
            if ($this->regulatedOrganization->administrators()->count() === 1 && $this->isAdministratorOf($this->regulatedOrganization)) {
                return true;
            }
        }

        return false;
    }

    public function twoFactorAuthEnabled(): bool
    {
        return ! is_null($this->two_factor_secret);
    }

    public function isAdministrator(): bool
    {
        return $this->context === 'administrator';
    }

    public function scopeWhereAdministrator(Builder $query): Builder
    {
        return $query->where('context', 'administrator');
    }

    public function allNotifications(): LengthAwarePaginator
    {
        $notifications = new Collection();

        if ($this->context === 'organization') {
            $notifications = $notifications->merge($this->organization->notifications);

            foreach ($this->organization->projects as $project) {
                $notifications = $notifications->merge($project->notifications);
            }
        } elseif ($this->context === 'regulated-organization') {
            $notifications = $notifications->merge($this->regulatedOrganization->notifications);

            foreach ($this->regulatedOrganization->projects as $project) {
                $notifications = $notifications->merge($project->notifications);
            }
        } else {
            return $this->notifications->paginate(20);
        }

        return $notifications->sortByDesc('created_at')->paginate(20);
    }

    public function allUnreadNotifications(): LengthAwarePaginator
    {
        $notifications = new Collection();

        if ($this->context === 'organization') {
            $notifications = $notifications->merge($this->organization?->unreadNotifications ?? []);

            foreach ($this->organization?->projects ?? [] as $project) {
                $notifications = $notifications->merge($project->unreadNotifications);
            }
        } elseif ($this->context === 'regulated-organization') {
            $notifications = $notifications->merge($this->regulatedOrganization?->unreadNotifications ?? []);

            foreach ($this->regulatedOrganization?->projects ?? [] as $project) {
                $notifications = $notifications->merge($project->unreadNotifications);
            }
        } else {
            return $this->unreadNotifications->paginate(20);
        }

        return $notifications->sortByDesc('created_at')->paginate(20);
    }

    public function hasCompletedCourse(Course $course): bool
    {
        $modules = $course->modules;
        $quiz = $course->quiz ?? null;

        foreach ($modules as $module) {
            if (! $user->modules->contains($module) ||
                ! $user->pivot->completed_at) {
                return false;
            }
        }

        if ($quiz &&
            ! $user->quizzes->contains($quiz) ||
            $userQuiz->score < 0.75) {
            return false;
        }

        return true;
    }
}
