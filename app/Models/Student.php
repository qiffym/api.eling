<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function rombel_class()
    {
        return $this->belongsTo(RombelClass::class);
    }

    public function online_classes()
    {
        return $this->belongsToMany(OnlineClass::class, 'enrollment')->withTimestamps();
    }

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'student_assignment')
            ->withPivot(['file', 'submitted_at', 'status', 'score'])
            ->using(StudentAssignment::class);
    }
}
