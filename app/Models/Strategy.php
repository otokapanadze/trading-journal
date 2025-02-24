<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $name
 * @property string $description
 * @property array $images
 * @property string $created_at
 * @property string $updated_at
 * @property int $id
 * @property int $account_id
 *
 */
class Strategy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'images',
        'account_id',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function account(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_strategies');
    }

    public function trades()
    {
        return $this->belongsToMany(Trade::class, 'trade_strategies');
    }
}
