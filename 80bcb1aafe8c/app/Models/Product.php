<?php

namespace App\Models;

use App\Support\Annotations\HasEloquentAnnotations;
use App\Support\Enums\Currency\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property string $title
 * @property string $description
 * @property float $price
 *
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product withConvertedPrice(Currency $currency)
 * @method static Builder|Product withFormattedPrice(Currency $currency)
 */
class Product extends Model
{
    use HasEloquentAnnotations;
    use HasUlids;
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        $clearCache = fn() => Cache::tags(['products'])->flush();

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    protected $table = 'products';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function scopeWithConvertedPrice(Builder $query, Currency $currency): Builder
    {
        $rate = $currency->value;

        return $query->selectRaw(
            "*, ROUND(price / ?, 2) as price",
            [$rate]
        );
    }

    public function scopeWithFormattedPrice(Builder $query, Currency $currency): Builder
    {
        $rate = $currency->value;
        $format = $currency->getFormat()->value;

        return $query->selectRaw(
            "*, REPLACE(?, 'x', CAST(ROUND(price / ?, 2) AS TEXT)) as price",
            [$format, $rate]
        );
    }
}
