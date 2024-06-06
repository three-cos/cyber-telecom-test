<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * \App\Models\CarModelGeneration
 *
 * @property int $id
 * @property int $model_id
 * @property string $market
 * @property string $name
 * @property string $human_readable_name
 * @property string $external_id
 * @property string $time
 * @property string|null $image
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CarModel $model
 * @method static \Illuminate\Database\Eloquent\Builder|CarModelGeneration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModelGeneration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModelGeneration query()
 * @mixin \Eloquent
 */
class CarModelGeneration extends Model
{
    protected $table = 'car_model_generations';

    protected $guarded = [];

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }
}
