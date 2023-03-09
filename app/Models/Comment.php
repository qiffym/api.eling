<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function forum(): BelongsTo
    {
        return $this->belongsTo(DiscussionForum::class, 'discussion_forum_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subComments(): HasMany
    {
        return $this->hasMany(SubComment::class);
    }
}
