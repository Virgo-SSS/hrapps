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

    /*
     * Relationships
     */
    public function cuti(): BelongsTo
    {
        return $this->belongsTo(Cuti::class, 'cuti_id');
    }

    public function head_division(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_of_division');
    }

    public function head_department(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_of_department');
    }


    /*
     * Accessor & Mutator
     */
    public function getStatusHodInHumanAttribute(): string
    {
        return $this->status_hod == 0 ? 'Pending' : ($this->status_hod == 1 ? 'Approved' : 'Rejected');
    }

    public function getStatusHodpInHumanAttribute(): string
    {
        return $this->status_hodp == 0 ? 'Pending' : ($this->status_hodp == 1 ? 'Approved' : 'Rejected');
    }

    public function getColorStatusHodAttribute(): string
    {
        return $this->status_hod == 0 ? 'warning' : ($this->status_hod == 1 ? 'success' : 'danger');
    }

    public function getColorStatusHodpAttribute(): string
    {
        return $this->status_hodp == 0 ? 'warning' : ($this->status_hodp == 1 ? 'success' : 'danger');
    }
}
