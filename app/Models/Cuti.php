<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';

    protected $fillable = [
        'user_id',
        'from',
        'to',
        'reason',
        'status',
    ];

    /*
     *  Relationships
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cutiRequest(): HasOne
    {
        return $this->hasOne(CutiRequest::class, 'cuti_id');
    }

    /*
     * Scope Query
     *
     */
    public function scopePending($query): Builder
    {
        return $query->where('status', config('cuti.status.pending'));
    }

    public function scopeApproved(): Builder
    {
        return $this->where('status', config('cuti.status.approved'));
    }


    /*
     *  Accessors And Mutators
     *
     */
    public function getDurationAttribute(): int
    {
        $from = Carbon::parse($this->from);
        $to = Carbon::parse($this->to);
        return $to->diffInDays($from) + 1;
    }

    public function getStatusInHumanAttribute(): string
    {
        return $this->status == 0 ? 'Pending' : ($this->status == 1 ? 'Approved' : 'Rejected');
    }

    public function getColorStatusAttribute(): string
    {
        return $this->status == 0 ? 'warning' : ($this->status == 1 ? 'success' : 'danger');
    }

    public function getDateCutiAttribute(): string
    {
        return $this->from . ' - ' . $this->to;
    }

    public function getTotalLeaveDaysAttribute(): int
    {
        // sub from and to date
        $from = Carbon::parse($this->from);
        $to = Carbon::parse($this->to);

        // get total days
        $totalDays = $to->diffInDays($from) + 1;

        return $totalDays;
    }
}
