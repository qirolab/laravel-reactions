<?php

namespace Hkp22\Laravel\Reactions\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reactions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
    ];

    /**
     * Reactable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reactable()
    {
        return $this->morphTo();
    }
}
