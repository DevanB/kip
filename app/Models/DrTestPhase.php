<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrTestPhase extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'dr_test_id',
        'title',
        'started_at',
        'finished_at',
        'duration_minutes',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<DrTest, $this> */
    public function drTest(): BelongsTo
    {
        return $this->belongsTo(DrTest::class);
    }
}
