<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'symbol',
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
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
