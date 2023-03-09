<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class OnlineClass extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollment')->withTimestamps();
    }

    public function assignments(): HasManyThrough
    {
        return $this->hasManyThrough(Assignment::class, OnlineClassContent::class);
    }

    public function rombel_class()
    {
        return $this->belongsTo(RombelClass::class, 'rombel_class_id');
    }

    public function contents()
    {
        return $this->hasMany(OnlineClassContent::class);
    }
}
