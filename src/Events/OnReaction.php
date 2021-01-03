<?php

namespace Qirolab\Laravel\Reactions\Events;

use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Qirolab\Laravel\Reactions\Models\Reaction;

class OnReaction
{
    /**
     * The reactable model.
     *
     * @var ReactableInterface
     */
    public $reactable;

    /**
     * User who reacted on model.
     *
     * @var ReactsInterface
     */
    public $reactBy;

    /**
     * Reaction model.
     *
     * @var Reaction
     */
    public $reaction;

    /**
     * Create a new event instance.
     *
     * @param ReactableInterface $reactable
     * @param Reaction           $reaction
     * @param ReactsInterface    $reactBy
     *
     * @return void
     */
    public function __construct(ReactableInterface $reactable, Reaction $reaction, ReactsInterface $reactBy)
    {
        $this->reactable = $reactable;
        $this->reaction = $reaction;
        $this->reactBy = $reactBy;
    }
}
