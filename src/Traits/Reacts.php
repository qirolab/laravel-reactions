<?php

namespace Qirolab\Laravel\Reactions\Traits;

use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Events\OnDeleteReaction;
use Qirolab\Laravel\Reactions\Events\OnReaction;
use Qirolab\Laravel\Reactions\Models\Reaction;

trait Reacts
{
    /**
     * Reaction on reactable model.
     *
     * @param  ReactableInterface $reactable
     * @param  mixed              $type
     * @return Reaction
     */
    public function reactTo(ReactableInterface $reactable, $type)
    {
        $reaction = $reactable->reactions()->where([
            'user_id' => $this->getKey(),
        ])->first();

        if (! $reaction) {
            return $this->storeReaction($reactable, $type);
        }

        if ($reaction->type == $type) {
            return $reaction;
        }

        $this->deleteReaction($reaction, $reactable);

        return $this->storeReaction($reactable, $type);
    }

    /**
     * Remove reaction from reactable model.
     *
     * @param  ReactableInterface $reactable
     * @return void
     */
    public function removeReactionFrom(ReactableInterface $reactable)
    {
        $reaction = $reactable->reactions()->where([
            'user_id' => $this->getKey(),
        ])->first();

        if (! $reaction) {
            return;
        }

        $this->deleteReaction($reaction, $reactable);
    }

    /**
     * Toggle reaction on reactable model.
     *
     * @param  ReactableInterface $reactable
     * @param  mixed              $type
     * @return void
     */
    public function toggleReactionOn(ReactableInterface $reactable, $type)
    {
        $reaction = $reactable->reactions()->where([
            'user_id' => $this->getKey(),
        ])->first();

        if (! $reaction) {
            return $this->storeReaction($reactable, $type);
        }

        $this->deleteReaction($reaction, $reactable);

        if ($reaction->type == $type) {
            return;
        }

        return $this->storeReaction($reactable, $type);
    }

    /**
     * Reaction on reactable model.
     *
     * @param  ReactableInterface $reactable
     * @return Reaction
     */
    public function ReactedOn(ReactableInterface $reactable)
    {
        return $reactable->reacted($this);
    }

    /**
     * Check is reacted on reactable model.
     *
     * @param  ReactableInterface $reactable
     * @param  mixed              $type
     * @return bool
     */
    public function isReactedOn(ReactableInterface $reactable, $type = null)
    {
        $isReacted = $reactable->reactions()->where([
            'user_id' => $this->getKey(),
        ]);

        if ($type) {
            $isReacted->where([
                'type' => $type,
            ]);
        }

        return $isReacted->exists();
    }

    /**
     * Store reaction.
     *
     * @param  ReactableInterface                       $reactable
     * @param  mixed                                    $type
     * @return \Qirolab\Laravel\Reactions\Models\Reaction
     */
    protected function storeReaction(ReactableInterface $reactable, $type)
    {
        $reaction = $reactable->reactions()->create([
            'user_id' => $this->getKey(),
            'type' => $type,
        ]);

        event(new OnReaction($reactable, $reaction, $this));

        return $reaction;
    }

    /**
     * Delete reaction.
     *
     * @param  Reaction           $reaction
     * @param  ReactableInterface $reactable
     * @return void
     */
    protected function deleteReaction(Reaction $reaction, ReactableInterface $reactable)
    {
        $response = $reaction->delete();

        event(new OnDeleteReaction($reactable, $reaction, $this));

        return $response;
    }
}
