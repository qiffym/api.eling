<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Submission extends Pivot
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(SubmissionStatus::class, 'status_id');
    }
}
