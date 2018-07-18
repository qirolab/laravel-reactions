<?php

namespace Hkp22\Laravel\Reactions\Events;

use Hkp22\Laravel\Reactions\Models\Reaction;
use Hkp22\Laravel\Reactions\Contracts\ReactsInterface;
use Hkp22\Laravel\Reactions\Contracts\ReactableInterface;

class OnDeleteReaction
{
    /**
     * The reactable model.
     *
     * @var \Hkp22\Laravel\Reactions\Traits\Reactable
     */
    public $reactable;

    /**
     * User who reacted on model.
     *
     * @var \Hkp22\Laravel\Reactions\Traits\Reacts
     */
    public $reactBy;

    /**
     * Reaction model.
     *
     * @var \Hkp22\Laravel\Reactions\Models\Reaction
     */
    public $reaction;

    /**
     * Create a new event instance.
     *
     * @param  \Hkp22\Laravel\Reactions\Traits\Reactable $reactable
     * @param  \Hkp22\Laravel\Reactions\Traits\Reacts    $reactBy
     * @return void
     */
    public function __construct(ReactableInterface $reactable, Reaction $reaction, ReactsInterface $reactBy)
    {
        $this->reactable = $reactable;
        $this->reaction = $reaction;
        $this->reactBy = $reactBy;
    }
}
