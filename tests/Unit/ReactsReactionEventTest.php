<?php

namespace Hkp22\Tests\Laravel\Reactions\Unit;

use Hkp22\Tests\Laravel\Reactions\TestCase;
use Hkp22\Laravel\Reactions\Events\OnReaction;
use Hkp22\Laravel\Reactions\Events\OnDeleteReaction;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\User;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\Article;

class ReactsReactionEventTest extends TestCase
{
    /** @test */
    public function it_can_fire_model_was_reacted_event()
    {
        $this->expectsEvents(OnReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $user->reactTo($article, 'like');
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event_on_toggle_reaction()
    {
        $this->expectsEvents(OnReaction::class);

        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        $user->toggleReactionOn($article, 'like');
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

        $user->reactTo($article, 'clap');
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

        $user->toggleReactionOn($article, 'clap');
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

        $user->removeReactionFrom($article);
    }
}
