<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DrTest extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'test_date',
        'rto_minutes',
        'rpo_minutes',
        'notes',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'test_date' => 'date',
        ];
    }

    /** @return HasMany<DrTestPhase, $this> */
    public function phases(): HasMany
    {
        return $this->hasMany(DrTestPhase::class);
    }
}
