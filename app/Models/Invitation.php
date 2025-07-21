<?php

namespace App\Models;

use App\Traits\RetrievesUserByNormalizedEmail;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invitation extends Model
{
    use HasFactory;
    use RetrievesUserByNormalizedEmail;

    protected $table = 'invitations';

    protected $fillable = [
        'email',
        'role',
        'type',
    ];

    protected static function newFactory(): Factory
    {
        return InvitationFactory::new();
    }

    /** @return MorphTo<Organization|RegulatedOrganization|Engagement, $this> */
    public function invitationable(): MorphTo
    {
        /** @var MorphTo<Organization|RegulatedOrganization|Engagement, $this> */
        return $this->morphTo();
    }

    public function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    public function accept(?string $type = null): void
    {
        if ($type) {
            if ($type === 'individual') {
                $user = $this->retrieveUserByEmail($this->email);
                $invitee = $user->individual;
                if ($this->role === 'connector') {
                    $this->invitationable->connector()->associate($invitee);
                    $this->invitationable->save();
                }
                if ($this->role === 'participant') {
                    $this->invitationable->participants()->save($invitee, ['status' => 'confirmed']);
                }
            }
            if ($type === 'organization') {
                $invitee = Organization::where('contact_person_email', $this->email)->first();
                if ($this->role === 'connector') {
                    $this->invitationable->organizationalConnector()->associate($invitee);
                    $this->invitationable->save();
                }
            }
        } else {
            $invitee = $this->retrieveUserByEmail($this->email);

            $this->invitationable->users()->attach(
                $invitee,
                ['role' => $this->role]
            );
        }

        $this->delete();
    }
}
