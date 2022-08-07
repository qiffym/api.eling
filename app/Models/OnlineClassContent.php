<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClassContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function online_class()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
