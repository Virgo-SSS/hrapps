<?php

namespace App\Models;

use Carbon\Carbon;
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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cutiRequest(): HasOne
    {
        return $this->hasOne(CutiRequest::class, 'cuti_id');
    }

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
}
