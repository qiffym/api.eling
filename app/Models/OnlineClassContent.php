<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineClassContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function online_class(): BelongsTo
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function forums(): HasMany
    {
        return $this->hasMany(DiscussionForum::class, 'online_class_content_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'online_class_content_id');
    }
}
