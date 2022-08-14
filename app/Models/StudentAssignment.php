<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentAssignment extends Pivot
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusStudentAssignment::class, 'status');
    }
}
