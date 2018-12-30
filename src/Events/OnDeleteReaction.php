<?php

namespace Qirolab\Laravel\Reactions\Events;

use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;

class OnDeleteReaction
{
    /**
     * The reactable model.
     *
     * @var \Qirolab\Laravel\Reactions\Traits\Reactable
     */
    public $reactable;

    /**
     * User who reacted on model.
     *
     * @var \Qirolab\Laravel\Reactions\Traits\Reacts
     */
    public $reactBy;

    /**
     * Reaction model.
     *
     * @var \Qirolab\Laravel\Reactions\Models\Reaction
     */
    public $reaction;

    /**
     * Create a new event instance.
     *
     * @param  \Qirolab\Laravel\Reactions\Traits\Reactable $reactable
     * @param  \Qirolab\Laravel\Reactions\Traits\Reacts    $reactBy
     * @return void
     */
    public function __construct(ReactableInterface $reactable, Reaction $reaction, ReactsInterface $reactBy)
    {
        $this->reactable = $reactable;
        $this->reaction = $reaction;
        $this->reactBy = $reactBy;
    }
}
