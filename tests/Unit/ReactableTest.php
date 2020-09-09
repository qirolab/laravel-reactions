<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactableTest extends TestCase
{
    /** @test */
    public function it_can_react_by_current_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $article->react('like');

        $this->assertEquals(1, $article->reactions->count());

        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_react_by_concrete_user()
    {
        $article = $this->createArticle();

        $user1 = $this->createUser();

        $user2 = $this->createUser();

        $this->actingAs($user1);

        $article->react('like', $user2);

        $this->assertEquals($user2->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_has_multiple_reactions()
    {
        $article = $this->createArticle();

        $users = $this->createUser([], 5);

        foreach ($users as $key => $user) {
            $article->react('like', $user);
        }

        $this->assertEquals(5, $article->reactions->count());
    }

    /** @test */
    public function it_cannot_duplicate_react()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $article->react('like', $user);
        $article->react('like', $user);

        $this->assertEquals(1, $article->reactions->count());
    }

    /** @test */
    public function it_can_remove_reaction()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $article->react('like', $user);

        $article->removeReaction();

        $this->assertEquals(0, $article->reactions->count());
    }

    /** @test */
    public function it_cannot_remove_reaction_by_user_if_not_reacted()
    {
        $article = $this->createArticle();

        $user1 = $this->createUser();
        $article->react('like', $user1);

        $user2 = $this->createUser();
        $article->removeReaction($user2);

        $this->assertEquals(1, $article->reactions->count());
    }

    /** @test */
    public function it_can_add_reaction_with_toggle_by_current_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $article->toggleReaction('like');

        $this->assertEquals(1, $article->reactions->count());

        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_toggle_reaction_type_by_current_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $reaction = $article->toggleReaction('like');
        $this->assertInstanceOf(Reaction::class, $reaction);
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals($user->id, $article->reactions()->first()->user_id);
        $this->assertEquals('like', $article->reactions()->first()->type);

        $reaction = $article->toggleReaction('clap');
        $this->assertInstanceOf(Reaction::class, $reaction);
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals($user->id, $article->reactions()->first()->user_id);
        $this->assertEquals('clap', $article->reactions()->first()->type);
    }

    /** @test */
    public function it_can_remove_reaction_with_toggle_by_current_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $article->react('like');

        $article->toggleReaction('like');

        $this->assertEquals(0, $article->reactions->count());
    }

    /** @test */
    public function it_can_add_reaction_with_toggle_by_concrete_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $article->toggleReaction('like', $user);

        $this->assertEquals(1, $article->reactions->count());

        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }

    /** @test */
    public function it_can_toggle_reaction_type_by_concrete_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $article->toggleReaction('like', $user);
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals($user->id, $article->reactions()->first()->user_id);
        $this->assertEquals('like', $article->reactions()->first()->type);

        $article->toggleReaction('clap', $user);
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals($user->id, $article->reactions()->first()->user_id);
        $this->assertEquals('clap', $article->reactions()->first()->type);
    }

    /** @test */
    public function it_can_remove_reaction_with_toggle_by_concrete_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $article->react('like', $user);

        $article->toggleReaction('like', $user);

        $this->assertEquals(0, $article->reactions->count());
    }

    /** @test */
    public function it_can_check_if_entity_reacted_by_current_user()
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $article = $this->createArticle();

        $article->react('like');

        $this->assertTrue($article->isReactBy());
    }

    /** @test */
    public function it_can_check_if_entity_liked_by_concrete_user()
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        $article = $this->createArticle();

        $article->react('like', $user1);

        $this->assertTrue($article->isReactBy($user1));

        $this->assertFalse($article->isReactBy($user2));
    }

    /** @test */
    public function it_can_check_if_entity_liked_by_current_user_using_attribute()
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $article = $this->createArticle();

        $article->react('like');

        $this->assertTrue($article->is_reacted);
    }

    /** @test */
    public function it_can_has_reaction_summery()
    {
        $article = $this->createArticle();

        $users = $this->createUser([], 5);
        foreach ($users as $key => $user) {
            $article->react('like', $user);
        }

        $users = $this->createUser([], 2);
        foreach ($users as $key => $user) {
            $article->react('dislike', $user);
        }

        $users = $this->createUser([], 4);
        foreach ($users as $key => $user) {
            $article->react('clap', $user);
        }

        $users = $this->createUser([], 1);
        foreach ($users as $key => $user) {
            $article->react('hooray', $user);
        }

        $summaryAsArray = $article->reactionSummary()->toArray();

        $this->assertEquals([
            'like' => 5,
            'dislike' => 2,
            'clap' => 4,
            'hooray' => 1,
        ], $summaryAsArray);
    }

    /** @test */
    public function it_can_has_reaction_summery_attribute()
    {
        $article = $this->createArticle();

        $users = $this->createUser([], 5);
        foreach ($users as $key => $user) {
            $article->react('like', $user);
        }

        $users = $this->createUser([], 2);
        foreach ($users as $key => $user) {
            $article->react('dislike', $user);
        }

        $users = $this->createUser([], 4);
        foreach ($users as $key => $user) {
            $article->react('clap', $user);
        }

        $users = $this->createUser([], 1);
        foreach ($users as $key => $user) {
            $article->react('hooray', $user);
        }

        $summaryAsArray = $article->reaction_summary->toArray();

        $this->assertEquals([
            'like' => 5,
            'dislike' => 2,
            'clap' => 4,
            'hooray' => 1,
        ], $summaryAsArray);
    }

    /** @test **/
    public function it_can_has_collection_of_reactions_by_users()
    {
        $article = $this->createArticle();

        $users = $this->createUser([], 5);
        foreach ($users as $key => $user) {
            if ($key >= 3) {
                $article->react('like', $user);
            } else {
                $article->react('clap', $user);
            }
        }

        $this->assertEquals($users->toArray(), $article->reactionsBy()->toArray());
    }

    /** @test **/
    public function it_can_has_collection_of_reactions_by_users_using_attribute()
    {
        $article = $this->createArticle();

        $users = $this->createUser([], 5);
        foreach ($users as $key => $user) {
            if ($key >= 3) {
                $article->react('like', $user);
            } else {
                $article->react('clap', $user);
            }
        }

        $this->assertEquals($users->toArray(), $article->reactions_by->toArray());
    }

    /** @test **/
    public function it_can_has_reacted_reactions_by_current_login_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $this->actingAs($user);

        $article->react('like');

        $this->assertInstanceOf(Reaction::class, $article->reacted());
        $this->assertInstanceOf(Reaction::class, $article->reacted);
        $this->assertEquals('like', $article->reacted()->type);
        $this->assertEquals('like', $article->reacted->type);
    }

    /** @test **/
    public function it_can_has_reacted_reactions_by_given_user()
    {
        $article = $this->createArticle();

        $user = $this->createUser();

        $article->react('like', $user);

        $this->assertInstanceOf(Reaction::class, $article->reacted($user));
        $this->assertEquals('like', $article->reacted($user)->type);
    }
}
