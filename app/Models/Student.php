<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory, Notifiable;

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address and name...
        return [$this->user->email => $this->user->name];
    }

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
            ->withTimestamps()
            ->withPivot(['file', 'submitted_at', 'status_id', 'score'])
            ->using(StudentAssignment::class);
    }
}
