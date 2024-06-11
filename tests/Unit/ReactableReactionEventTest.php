<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Illuminate\Support\Facades\Event;
use Qirolab\Laravel\Reactions\Events\OnDeleteReaction;
use Qirolab\Laravel\Reactions\Events\OnReaction;
use Qirolab\Laravel\Reactions\Helper;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactableReactionEventTest extends TestCase
{
    protected $article;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->article = $this->createArticle();
        $this->user = $this->createUser();
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event()
    {
        $this->article->react('like', $this->user);
        Event::assertDispatched(OnReaction::class);
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event_on_toggle_reaction()
    {
        $this->article->toggleReaction('like', $this->user);
        Event::assertDispatched(OnReaction::class);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction()
    {
        $this->article->reactions()->create([
            Helper::resolveReactsIdColumn() => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->article->react('clap', $this->user);

        Event::assertDispatched(OnDeleteReaction::class);
        Event::assertDispatched(OnReaction::class);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction_via_toggle()
    {
        $this->article->reactions()->create([
            Helper::resolveReactsIdColumn() => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->article->toggleReaction('clap', $this->user);
        Event::assertDispatched(OnDeleteReaction::class);
        Event::assertDispatched(OnReaction::class);
    }

    /** @test **/
    public function it_can_fire_reaction_was_deleted_event()
    {
        $this->article->reactions()->create([
            Helper::resolveReactsIdColumn() => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->article->removeReaction($this->user);
        Event::assertDispatched(OnDeleteReaction::class);
    }
}
