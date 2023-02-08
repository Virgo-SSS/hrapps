<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CutiRequest extends Model
{
    use HasFactory;

    protected $table = 'cuti_request';

    protected $fillable = [
        'cuti_id',
        'head_of_division',
        'status_hod',
        'note_hod',
        'approved_hod_at',
        'head_of_department',
        'status_hodp',
        'note_hodp',
        'approved_hodp_at',
    ];

    public function cuti(): BelongsTo
    {
        return $this->belongsTo(Cuti::class, 'cuti_id');
    }

    public function head_of_division(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_of_divsion');
    }

    public function head_of_department(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_of_department');
    }
}
