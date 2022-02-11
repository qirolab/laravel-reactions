<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Illuminate\Foundation\Testing\Concerns\MocksApplicationServices;
use Qirolab\Laravel\Reactions\Events\OnDeleteReaction;
use Qirolab\Laravel\Reactions\Events\OnReaction;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactableReactionEventTest extends TestCase
{
    use MocksApplicationServices;

    protected $article;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->article = $this->createArticle();
        $this->user = $this->createUser();
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event()
    {
        $this->expectsEvents(OnReaction::class);

        $this->article->react('like', $this->user);
    }

    /** @test */
    public function it_can_fire_model_was_reacted_event_on_toggle_reaction()
    {
        $this->expectsEvents(OnReaction::class);

        $this->article->toggleReaction('like', $this->user);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction()
    {
        $this->expectsEvents(OnDeleteReaction::class);
        $this->expectsEvents(OnReaction::class);

        $this->article->reactions()->create([
            'user_id' => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->article->react('clap', $this->user);
    }

    /** @test */
    public function it_can_fire_reaction_deleted_and_model_was_reacted_event_on_change_reaction_via_toggle()
    {
        $this->expectsEvents(OnDeleteReaction::class);
        $this->expectsEvents(OnReaction::class);

        $this->article->reactions()->create([
            'user_id' => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->article->toggleReaction('clap', $this->user);
    }

    /** @test **/
    public function it_can_fire_reaction_was_deleted_event()
    {
        $this->expectsEvents(OnDeleteReaction::class);

        $this->article->reactions()->create([
            'user_id' => $this->user->getKey(),
            'type' => 'like',
        ]);

        $this->article->removeReaction($this->user);
    }
}
