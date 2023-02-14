<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Illuminate\Support\Facades\Event;
use Qirolab\Laravel\Reactions\Events\OnDeleteReaction;
use Qirolab\Laravel\Reactions\Events\OnReaction;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactsReactionEventTest extends TestCase
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
        $this->user->reactTo($this->article, 'like');
        Event::assertDispatched(OnReaction::class);
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event_on_toggle_reaction()
    {
        $this->user->toggleReactionOn($this->article, 'like');
        Event::assertDispatched(OnReaction::class);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction()
    {
        $this->article->reactions()->create([
            'user_id' => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->user->reactTo($this->article, 'clap');

        Event::assertDispatched(OnDeleteReaction::class);
        Event::assertDispatched(OnReaction::class);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction_via_toggle()
    {
        $this->article->reactions()->create([
            'user_id' => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->user->toggleReactionOn($this->article, 'clap');

        Event::assertDispatched(OnDeleteReaction::class);
        Event::assertDispatched(OnReaction::class);
    }

    /** @test **/
    public function it_can_fire_reaction_was_deleted_event()
    {
        $this->article->reactions()->create([
            'user_id' => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->user->removeReactionFrom($this->article);
        Event::assertDispatched(OnDeleteReaction::class);
    }
}
