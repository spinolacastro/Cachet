<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Models;

use AltThree\Validator\ValidatingTrait;
use CachetHQ\Cachet\Presenters\MetricPointPresenter;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class MetricPoint extends Model implements HasPresenter
{
    use ValidatingTrait;

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'metric_id' => 'int',
        'value'     => 'int',
        'counter'   => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['metric_id', 'value', 'counter', 'created_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var string[]
     */
    protected $hidden = ['counter'];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'value' => 'numeric|required',
    ];

    /**
     * A metric point belongs to a metric unit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metric()
    {
        return $this->belongsTo(Metric::class, 'id', 'metric_id');
    }

    /**
     * Override the value attribute.
     *
     * @return float
     */
    public function getValueAttribute()
    {
        if ($this->metric->calc_type === Metric::CALC_SUM) {
            return $this->value * $this->counter;
        } elseif ($this->metric->calc_type === Metric::CALC_AVG) {
            return $this->value * $this->counter;
        }
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return MetricPointPresenter::class;
    }
}
