<?php

namespace Qirolab\Laravel\Reactions\Traits;

use Illuminate\Database\Eloquent\Builder;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Qirolab\Laravel\Reactions\Exceptions\InvalidReactionUser;
use Qirolab\Laravel\Reactions\Models\Reaction;

trait Reactable
{
    /**
     * Collection of reactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    /**
     * Get collection of users who reacted on reactable model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function reactionsBy()
    {
        $userModel = $this->resolveUserModel();

        $userIds = $this->reactions->pluck('user_id');

        return $userModel::whereKey($userIds)->get();
    }

    /**
     * Attribute to get collection of users who reacted on reactable model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getReactionsByAttribute()
    {
        return $this->reactionsBy();
    }

    /**
     * Reaction summary.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function reactionSummary()
    {
        return $this->reactions->groupBy('type')->map(function ($val) {
            return $val->count();
        });
    }

    /**
     * Reaction summary attribute.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getReactionSummaryAttribute()
    {
        return $this->reactionSummary();
    }

    /**
     * Add reaction.
     *
     * @param  mixed  $reactionType
     * @param  mixed  $user
     * @return Reaction|bool
     */
    public function react($reactionType, $user = null)
    {
        $user = $this->getUser($user);

        if ($user) {
            return $user->reactTo($this, $reactionType);
        }

        return false;
    }

    /**
     * Remove reaction.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function removeReaction($user = null)
    {
        $user = $this->getUser($user);

        if ($user) {
            return $user->removeReactionFrom($this);
        }

        return false;
    }

    /**
     * Toggle Reaction.
     *
     * @param  mixed  $reactionType
     * @param  mixed  $user
     * @return void|Reaction
     */
    public function toggleReaction($reactionType, $user = null)
    {
        $user = $this->getUser($user);

        if ($user) {
            return $user->toggleReactionOn($this, $reactionType);
        }
    }

    /**
     * Reaction on reactable model by user.
     *
     * @param  mixed  $user
     * @return Reaction
     */
    public function reacted($user = null)
    {
        $user = $this->getUser($user);

        return $this->reactions->where('user_id', $user->getKey())->first();
    }

    /**
     * Reaction on reactable model by user.
     *
     * @return Reaction
     */
    public function getReactedAttribute()
    {
        return $this->reacted();
    }

    /**
     * Check is reacted by user.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function isReactBy($user = null, $type = null)
    {
        $user = $this->getUser($user);

        if ($user) {
            return $user->isReactedOn($this, $type);
        }

        return false;
    }

    /**
     * Check is reacted by user.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function getIsReactedAttribute()
    {
        return $this->isReactBy();
    }

    /**
     * Fetch records that are reacted by a given user.
     *
     * @todo think about method name
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @param  null|int|ReactsInterface  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throw \Qirolab\Laravel\Reactions\Exceptions\InvalidReactionUser
     */
    public function scopeWhereReactedBy(Builder $query, $userId = null, $type = null)
    {
        $user = null;

        try {
            $user = $this->getUser($userId);
        } catch (InvalidReactionUser $e) {
            if (! $user && ! $userId) {
                throw InvalidReactionUser::notDefined();
            }
        }

        $userId = ($user) ? $user->getKey() : $userId;

        return $query->whereHas('reactions', function ($innerQuery) use ($userId, $type) {
            $innerQuery->where('user_id', $userId);

            if ($type) {
                $innerQuery->where('type', $type);
            }
        });
    }

    /**
     * Get user model.
     *
     * @param  mixed  $user
     * @return ReactsInterface
     *
     * @throw \Qirolab\Laravel\Reactions\Exceptions\InvalidReactionUser
     */
    private function getUser($user = null)
    {
        if (! $user && auth()->check()) {
            return auth()->user();
        }

        if ($user instanceof ReactsInterface) {
            return $user;
        }

        if (! $user) {
            throw InvalidReactionUser::notDefined();
        }

        throw InvalidReactionUser::invalidReactByUser();
    }

    /**
     * Retrieve User's model class name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModel()
    {
        return config('auth.providers.users.model');
    }
}
