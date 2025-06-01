<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Session extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'trading_sessions';

    protected $fillable = [
        'account_id',
        'name',
        'start',
        'end'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class, 'trade_session_id');
    }
}
