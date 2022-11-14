<?php

namespace Qirolab\Laravel\Reactions\Models;

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

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('reactions.table_name') ?? 'reactions');
        parent::__construct($attributes);
    }

    /**
     * Reactable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reactable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that reacted on reactable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reactBy()
    {
        $userModel = config('auth.providers.users.model');

        return $this->belongsTo($userModel, 'user_id');
    }
}
