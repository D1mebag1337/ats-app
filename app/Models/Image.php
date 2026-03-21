<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    protected $table = 'images';
    protected $primaryKey = 'ImageID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Alternativtext',
        'ImageBinary',
    ];

    protected $hidden = [
        'ImageBinary',
    ];

    public function stellen(): HasMany
    {
        return $this->hasMany(Stelle::class, 'ImageID', 'ImageID');
    }
}
