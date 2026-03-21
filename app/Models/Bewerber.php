<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bewerber extends Model
{
    protected $table = 'bewerber';
    protected $primaryKey = 'UserID';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'UserID',
        'Name',
        'Vorname',
        'Gebdatum',
        'Strasse',
        'Hausnr',
        'Plz',
        'Ort',
    ];

    protected $casts = [
        'Gebdatum' => 'date',
        'Plz'      => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function bewerbungen(): HasMany
    {
        return $this->hasMany(Bewerbung::class, 'BewerberID', 'UserID');
    }
}
