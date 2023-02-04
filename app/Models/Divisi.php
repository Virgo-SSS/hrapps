<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';

    protected $fillable = [
        'name',
        'created_by',
        'edited_by',
        'is_active',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'divisi_id');
    }

    public function posisi()
    {
        return $this->hasMany(Posisi::class, 'divisi_id');
    }

    public function userProfile()
    {
        return $this->hasMany(UserProfile::class, 'divisi_id');
    }
}
