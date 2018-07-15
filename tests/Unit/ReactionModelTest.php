<?php

namespace Hkp22\Tests\Laravel\Reactions\Unit;

use Hkp22\Tests\Laravel\Reactions\TestCase;
use Hkp22\Laravel\Reactions\Models\Reaction;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\Article;

class ReactionModelTest extends TestCase
{
    /** @test */
    public function it_can_fill_user_id()
    {
        $reaction = new Reaction([
            'user_id' => 6
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
}
