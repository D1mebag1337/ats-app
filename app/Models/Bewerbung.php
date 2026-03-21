<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bewerbung extends Model
{
    protected $table = 'bewerbungen';
    protected $primaryKey = 'BewerbungID';
    public $incrementing = true;
    protected $keyType = 'int';

    const STATUS_EINGEGANGEN = 'Eingegangen';
    const STATUS_IN_PRUEFUNG = 'In Prüfung';
    const STATUS_EINGELADEN  = 'Eingeladen';
    const STATUS_ABGELEHNT   = 'Abgelehnt';
    const STATUS_ANGENOMMEN  = 'Angenommen';

    protected $fillable = [
        'UserID',
        'StellenID',
        'Anschreiben',
        'Lebenslauf',
        'Zeugnisse',
        'Zertifikate',
        'Datenschutzerklaerung',
        'Status',
    ];

    protected $hidden = [
        'Anschreiben',
        'Lebenslauf',
        'Zeugnisse',
        'Zertifikate',
    ];

    protected $casts = [
        'Datenschutzerklaerung' => 'boolean',
    ];

    public function bewerber(): BelongsTo
    {
        return $this->belongsTo(Bewerber::class, 'UserID', 'UserID');
    }

    public function stelle(): BelongsTo
    {
        return $this->belongsTo(Stelle::class, 'StellenID', 'StellenID');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('Status', $status);
    }
}
