<?php

namespace Hkp22\Tests\Laravel\Reactions\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Hkp22\Laravel\Reactions\Traits\Reactable;
use Hkp22\Laravel\Reactions\Contracts\ReactableInterface;

class Article extends Model implements ReactableInterface
{
    use Reactable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];
}
