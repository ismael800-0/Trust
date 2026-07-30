<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TontineFlag extends Model
{
    protected $fillable = ['tontine_id', 'message', 'resolved'];
    protected $casts = ['resolved' => 'boolean'];

    public function tontine(): BelongsTo
    {
        return $this->belongsTo(Tontine::class);
    }
}