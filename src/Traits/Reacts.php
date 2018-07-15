<?php

namespace Hkp22\Laravel\Reactions\Traits;

use Hkp22\Laravel\Reactions\Models\Reaction;
use Hkp22\Laravel\Reactions\Contracts\ReactableInterface;

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

        if (!$reaction) {
            return $reactable->reactions()->create([
                'user_id' => $this->getKey(),
                'type' => $type,
            ]);
        }

        if ($reaction->type == $type) {
            return $reaction;
        }

        $reaction->delete();

        return $reactable->reactions()->create([
            'user_id' => $this->getKey(),
            'type' => $type,
        ]);
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

        if (!$reaction) {
            return;
        }

        $reaction->delete();
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
            'type' => $type
        ])->first();

        if (!$reaction) {
            $reactable->reactions()->create([
                'user_id' => $this->getKey(),
                'type' => $type,
            ]);

            return;
        }

        $reaction->delete();
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
}
