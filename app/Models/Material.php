<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['content'];

    public function content()
    {
        return $this->belongsTo(OnlineClassContent::class, 'online_class_content_id');
    }
}
