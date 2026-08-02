<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tontine extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'contribution_amount',
        'frequency',
        'max_members',
        'start_date',
        'current_round',
        'total_rounds_completed',
        'last_disbursement_at',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'last_disbursement_at' => 'datetime',
        'contribution_amount' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tontine_user')
            ->withPivot('status', 'position_in_cycle')
            ->withTimestamps();
    }

    public function contributions(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Contribution::class);
}

public function currentCycle(): int
{
    return $this->contributions()->max('cycle_number') ?? 1;
}

public function nextDueDate(): \Carbon\Carbon
{
    $start = \Carbon\Carbon::parse($this->start_date);

    return match ($this->frequency) {
        'daily' => $start->copy()->addDays($this->total_rounds_completed),
        'weekly' => $start->copy()->addWeeks($this->total_rounds_completed),
        'monthly' => $start->copy()->addMonths($this->total_rounds_completed),
    };
}

}