<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stelle extends Model
{
    protected $table = 'stellen';
    protected $primaryKey = 'StellenID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Name',
        'Beschreibung',
        'Kurzbeschreibung',
        'Aufgaben',
        'Voraussetzungen',
        'ImageID',
        'Online',
        'Arbeitsorte',
    ];

    protected $casts = [
        'Aufgaben'        => 'array',
        'Voraussetzungen' => 'array',
        'Online'          => 'boolean',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'ImageID', 'ImageID');
    }

    public function bewerbungen(): HasMany
    {
        return $this->hasMany(Bewerbung::class, 'StellenID', 'StellenID');
    }

    public function scopeOnline($query)
    {
        return $query->where('Online', true);
    }
}
