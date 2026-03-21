<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Role',
        'Email',
        'PW',
    ];

    protected $hidden = [
        'PW',
    ];

    const ROLE_HR       = 'H';
    const ROLE_BEWERBER = 'B';

    public function isHR(): bool       { return $this->Role === self::ROLE_HR; }
    public function isBewerber(): bool { return $this->Role === self::ROLE_BEWERBER; }

    public function bewerber(): HasOne
    {
        return $this->hasOne(Bewerber::class, 'UserID', 'UserID');
    }
}
