<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assignment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function content(): BelongsTo
    {
        return $this->belongsTo(OnlineClassContent::class, 'online_class_content_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_assignment')
            ->withPivot(['file', 'submitted_at', 'status', 'score'])
            ->using(StudentAssignment::class);
    }
}
