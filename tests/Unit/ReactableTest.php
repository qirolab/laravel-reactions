<?php

namespace Hkp22\Tests\Laravel\Reactions\Unit;

use Hkp22\Tests\Laravel\Reactions\TestCase;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\User;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\Article;

class ReactableTest extends TestCase
{
    /** @test */
    public function it_can_react_by_current_user()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article->react('like');

        $this->assertEquals(1, $article->reactions->count());

        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_react_by_concrete_user()
    {
        $article = factory(Article::class)->create();

        $user1 = factory(User::class)->create();

        $user2 = factory(User::class)->create();

        $this->actingAs($user1);

        $article->react('like', $user2);

        $this->assertEquals($user2->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_has_multiple_likes()
    {
        $article = factory(Article::class)->create();

        $users = factory(User::class, 5)->create();

        foreach ($users as $key => $user) {
            $article->react('like', $user);
        }

        $this->assertEquals(5, $article->reactions->count());
    }

    /** @test */
    public function it_cannot_duplicate_react()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $article->react('like', $user);
        $article->react('like', $user);

        $this->assertEquals(1, $article->reactions->count());
    }

    /** @test */
    public function it_can_remove_reaction()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article->react('like', $user);

        $article->removeReaction();

        $this->assertEquals(0, $article->reactions->count());
    }

    /** @test */
    public function it_cannot_remove_reaction_by_user_if_not_reacted()
    {
        $article = factory(Article::class)->create();

        $user1 = factory(User::class)->create();
        $article->react('like', $user1);

        $user2 = factory(User::class)->create();
        $article->removeReaction($user2);

        $this->assertEquals(1, $article->reactions->count());
    }

    /** @test */
    public function it_can_add_reaction_with_toggle_by_current_user()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article->toggleReaction('like');

        $this->assertEquals(1, $article->reactions->count());

        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_remove_reaction_with_toggle_by_current_user()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article->react('like');

        $article->toggleReaction('like');

        $this->assertEquals(0, $article->reactions->count());
    }

    /** @test */
    public function it_can_add_reaction_with_toggle_by_concrete_user()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $article->toggleReaction('like', $user);

        $this->assertEquals(1, $article->reactions->count());

        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_remove_reaction_with_toggle_by_concrete_user()
    {
        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $article->react('like', $user);

        $article->toggleReaction('like', $user);

        $this->assertEquals(0, $article->reactions->count());
    }

    /** @test */
    public function it_can_check_if_entity_reacted_by_current_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article = factory(Article::class)->create();

        $article->react('like');

        $this->assertTrue($article->isReactBy());
    }

    /** @test */
    public function it_can_check_if_entity_liked_by_concrete_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $article = factory(Article::class)->create();

        $article->react('like', $user1);

        $this->assertTrue($article->isReactBy($user1));

        $this->assertFalse($article->isReactBy($user2));
    }

    /** @test */
    public function it_can_check_if_entity_liked_by_current_user_using_attribute()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article = factory(Article::class)->create();

        $article->react('like');

        $this->assertTrue($article->isReacted);
    }
}
