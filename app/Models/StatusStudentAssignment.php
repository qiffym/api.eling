<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusStudentAssignment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function student_assignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }
}
