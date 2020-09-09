<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactsModelTest extends TestCase
{
    /** @test */
    public function it_can_react_to_reactable_model()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $user->reactTo($article, 'like');

        $this->assertEquals(1, $article->reactions()->count());

        $this->assertEquals($user->id, $article->reactions()->first()->user_id);
    }

    /** @test */
    public function it_can_react_to_reactable_by_other_user()
    {
        $article = $this->createArticle();

        $user1 = $this->createUser();

        $user2 = $this->createUser();

        $this->actingAs($user1);

        $user2->reactTo($article, 'like');

        $this->assertEquals(1, $article->reactions()->count());

        $this->assertEquals($user2->id, $article->reactions()->first()->user_id);
    }

    /** @test */
    public function it_can_reactions_many_reactables()
    {
        $user = $this->createUser();

        $article1 = $this->createArticle();
        $article2 = $this->createArticle();

        $user->reactTo($article1, 'like');
        $user->reactTo($article2, 'like');

        $this->assertEquals(1, $article1->reactions()->count());
        $this->assertEquals($user->id, $article1->reactions()->first()->user_id);

        $this->assertEquals(1, $article2->reactions()->count());
        $this->assertEquals($user->id, $article2->reactions()->first()->user_id);
    }

    /** @test */
    public function it_cannot_duplicate_react()
    {
        $user = $this->createUser();

        $article = $this->createArticle();

        $user->reactTo($article, 'like');
        $user->reactTo($article, 'dislike');

        $this->assertEquals(1, $article->reactions()->count());
    }

    /** @test */
    public function it_can_remove_reaction_on_reactable()
    {
        $user = $this->createUser();

        $article = $this->createArticle();

        $user->reactTo($article, 'like');

        $user->removeReactionFrom($article);

        $this->assertEquals(0, $article->reactions()->count());
    }

    /** @test */
    public function it_can_remove_reaction_from_reacted_reactable_model()
    {
        $user1 = $this->createUser();

        $user2 = $this->createUser();

        $article = $this->createArticle();

        $user2->reactTo($article, 'like');

        $user1->removeReactionFrom($article);

        $this->assertEquals(1, $article->reactions()->count());
    }

    /** @test */
    public function it_can_react_with_toggle()
    {
        $user = $this->createUser();

        $article = $this->createArticle();

        $user->toggleReactionOn($article, 'like');

        $this->assertEquals(1, $article->reactions()->count());
    }

    /** @test */
    public function it_can_toggle_reaction_type()
    {
        $user = $this->createUser();

        $article = $this->createArticle();

        $user->toggleReactionOn($article, 'like');
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals('like', $article->reactions()->first()->type);

        $user->toggleReactionOn($article, 'clap');
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals('clap', $article->reactions()->first()->type);
    }

    /** @test */
    public function it_can_remove_reaction_with_toggle()
    {
        $user = $this->createUser();

        $article = $this->createArticle();

        $user->reactTo($article, 'like');

        $user->toggleReactionOn($article, 'like');

        $this->assertEquals(0, $article->reactions()->count());
    }

    /** @test */
    public function it_can_check_if_reacted_on_reactable_model()
    {
        $user1 = $this->createUser();

        $article = $this->createArticle();

        $user1->reactTo($article, 'like');

        $this->assertTrue($user1->isReactedOn($article));

        $user2 = $this->createUser();

        $this->assertFalse($user2->isReactedOn($article));
    }

    /** @test **/
    public function it_can_have_reacted_reaction_on_reactable_model()
    {
        $user = $this->createUser();

        $article = $this->createArticle();

        $user->reactTo($article, 'like');

        $this->assertInstanceOf(Reaction::class, $user->reactedOn($article));
        $this->assertEquals('like', $user->reactedOn($article)->type);
    }
}
