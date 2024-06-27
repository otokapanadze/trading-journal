<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $description
 * @property array $images
 * @property string $created_at
 * @property string $updated_at
 * @property int $id
 */
class Strategy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function trades()
    {
        return $this->belongsToMany(Trade::class, 'trade_strategies');
    }
}
