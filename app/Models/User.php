<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'gender',
        'avatar',
        'birthday',
        'religion',
        'address',
        'telpon',
        'status',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'api';

    //# Accessors & Mutators
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    //# Eloquent Relationship
    public function gravatar($size = 150)
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?d=mm&s='.$size;
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function family()
    {
        return $this->hasOne(Family::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
