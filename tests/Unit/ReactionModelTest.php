<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Tests\Laravel\Reactions\TestCase;
use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\User;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Article;

class ReactionModelTest extends TestCase
{
    /** @test */
    public function it_can_fill_user_id()
    {
        $reaction = new Reaction([
            'user_id' => 6,
        ]);

        $this->assertEquals(6, $reaction->user_id);
    }

    /** @test */
    public function it_can_fill_type_id()
    {
        $like = new Reaction([
            'type' => 'like',
        ]);

        $this->assertEquals('like', $like->type);
    }

    /** @test */
    public function it_can_belong_to_reactable_model()
    {
        $article = factory(Article::class)->create();

        $article->reactions()->create([
            'user_id' => 23,
            'type' => 'like',
        ]);

        $this->assertInstanceOf(Article::class, Reaction::first()->reactable);
    }

    /** @test **/
    public function it_can_belong_to_user_model()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $article->reactions()->create([
            'user_id' => $user->getKey(),
            'type' => 'like',
        ]);

        $userModel = config('auth.providers.users.model');

        $this->assertInstanceOf($userModel, $article->reactions()->first()->reactBy);
        $this->assertInstanceOf(ReactsInterface::class, $article->reactions()->first()->reactBy);
    }
}
