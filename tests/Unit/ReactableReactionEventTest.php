<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Tests\Laravel\Reactions\TestCase;
use Qirolab\Laravel\Reactions\Events\OnReaction;
use Qirolab\Laravel\Reactions\Events\OnDeleteReaction;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\User;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Article;

class ReactableReactionEventTest extends TestCase
{
    /** @test */
    public function it_can_fire_model_was_reacted_event()
    {
        $this->expectsEvents(OnReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $article->react('like', $user);
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event_on_toggle_reaction()
    {
        $this->expectsEvents(OnReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $article->toggleReaction('like', $user);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction()
    {
        $this->expectsEvents(OnDeleteReaction::class);
        $this->expectsEvents(OnReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $article->reactions()->create([
            'user_id' => $user->getKey(),
            'type' => 'like',
        ]);

        $article->react('clap', $user);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction_via_toggle()
    {
        $this->expectsEvents(OnDeleteReaction::class);
        $this->expectsEvents(OnReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $article->reactions()->create([
            'user_id' => $user->getKey(),
            'type' => 'like',
        ]);

        $article->toggleReaction('clap', $user);
    }

    /** @test **/
    public function it_can_fire_reaction_was_deleted_event()
    {
        $this->expectsEvents(OnDeleteReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $article->reactions()->create([
            'user_id' => $user->getKey(),
            'type' => 'like',
        ]);

        $article->removeReaction($user);
    }
}
