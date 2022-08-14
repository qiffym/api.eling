<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscussionForum extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function content(): BelongsTo
    {
        return $this->belongsTo(OnlineClassContent::class, 'online_class_content_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
