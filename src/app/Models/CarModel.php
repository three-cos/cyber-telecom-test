<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * \App\Models\CarModel
 *
 * @property int $id
 * @property string $name
 * @property string $external_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CarModelGeneration> $generations
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel query()
 * @mixin \Eloquent
 */
class CarModel extends Model
{
    protected $table = 'car_models';

    protected $guarded = [];

    public function generations(): HasMany
    {
        return $this->hasMany(CarModelGeneration::class, 'model_id');
    }
}
