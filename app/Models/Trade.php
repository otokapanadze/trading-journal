<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $symbol
 * @property string $direction
 * @property float $pnl
 * @property string $open_at
 * @property string $closes_at
 * @property array $images
 * @property array $params
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property int $id
 * @property int $account_id
 */
class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol_id',
        'direction',
        'pnl',
        'open_at',
        'closes_at',
        'images',
        'params',
        'notes',
        'account_id'
    ];

    protected $casts = [
        'images' => 'array',
        'params' => 'array',
        'open_at' => 'datetime',
        'closes_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function symbol(): BelongsTo
    {
        return $this->belongsTo(Symbol::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'trade_session_id');
    }

    public function strategies(): BelongsToMany
    {
        return $this->belongsToMany(Strategy::class, 'trade_strategies');
    }
}
