<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Znck\Eloquent\Traits\BelongsToThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, BelongsToThrough, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'divisi_id',
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

    public function divisiMaker()
    {
        return $this->hasMany(Divisi::class, 'created_by');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function cuti(): HasMany
    {
        return $this->hasMany(Cuti::class, 'user_id');
    }

    public function head_of_division(): HasMany
    {
        return $this->hasMany(CutiRequest::class, 'head_of_division');
    }

    public function head_of_department(): HasMany
    {
        return $this->hasMany(CutiRequest::class, 'head_of_department');
    }
}
