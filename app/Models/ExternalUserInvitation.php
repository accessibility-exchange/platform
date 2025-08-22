<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExternalUserInvitation
 *
 * @property string $name
 */
class ExternalUserInvitation extends Model
{
    protected $table = 'external_user_invitation';

    protected $fillable = [
        'invitation_id',
        'user_email',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }
}
