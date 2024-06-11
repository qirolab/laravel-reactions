<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Profile;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactsProfileModelTest extends TestCase
{
    /**
    * Actions to be performed on PHPUnit start.
    *
    * @return void
    */
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('reactions.reacts_model', Profile::class);
        config()->set('reactions.reacts_id_column', 'profile_id');

        (new \CreateReactionsTable())->down();
        (new \CreateUsersTable())->down();
        (new \CreateArticlesTable())->down();

        (new \CreateReactionsTable())->up();
        (new \CreateUsersTable())->up();
        (new \CreateArticlesTable())->up();
    }

    /** @test */
    public function it_can_react_to_reactable_model()
    {
        $article = $this->createArticle();

        $user = $this->createUser();
        $profile = $this->createProfile();

        $this->actingAs($user);

        $profile->reactTo($article, 'like');

        $this->assertEquals(1, $article->reactions()->count());

        $this->assertTrue($profile->is($article->reactions()->first()->reactBy));
    }

    // /** @test */
    // public function it_can_react_to_reactable_by_other_user()
    // {
    //     $article = $this->createArticle();

    //     $user = $this->createUser();
    //     $profile1 = $this->createProfile();
    //     $profile2 = $this->createProfile();

    //     $this->actingAs($user);

    //     $profile2->reactTo($article, 'like');

    //     $this->assertEquals(1, $article->reactions()->count());

    //     $this->assertTrue($profile2->is($article->reactions()->first()->reactBy));
    // }

    /** @test */
    public function it_can_reactions_many_reactables()
    {
        $profile = $this->createProfile();

        $article1 = $this->createArticle();
        $article2 = $this->createArticle();

        $profile->reactTo($article1, 'like');
        $profile->reactTo($article2, 'like');

        $this->assertEquals(1, $article1->reactions()->count());
        $this->assertTrue($profile->is($article1->reactions()->first()->reactBy));

        $this->assertEquals(1, $article2->reactions()->count());
        $this->assertTrue($profile->is($article2->reactions()->first()->reactBy));
    }

    /** @test */
    public function it_cannot_duplicate_react()
    {
        $profile = $this->createProfile();

        $article = $this->createArticle();

        $profile->reactTo($article, 'like');
        $profile->reactTo($article, 'dislike');

        $this->assertEquals(1, $article->reactions()->count());
    }

    /** @test */
    public function it_can_remove_reaction_on_reactable()
    {
        $profile = $this->createProfile();

        $article = $this->createArticle();

        $profile->reactTo($article, 'like');

        $profile->removeReactionFrom($article);

        $this->assertEquals(0, $article->reactions()->count());
    }

    /** @test */
    public function it_can_remove_reaction_from_reacted_reactable_model()
    {
        $profile1 = $this->createProfile();

        $profile2 = $this->createProfile();

        $article = $this->createArticle();

        $profile2->reactTo($article, 'like');

        $profile1->removeReactionFrom($article);

        $this->assertEquals(1, $article->reactions()->count());
    }

    /** @test */
    public function it_can_react_with_toggle()
    {
        $profile = $this->createProfile();

        $article = $this->createArticle();

        $profile->toggleReactionOn($article, 'like');

        $this->assertEquals(1, $article->reactions()->count());
    }

    /** @test */
    public function it_can_toggle_reaction_type()
    {
        $profile = $this->createProfile();

        $article = $this->createArticle();

        $profile->toggleReactionOn($article, 'like');
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals('like', $article->reactions()->first()->type);

        $profile->toggleReactionOn($article, 'clap');
        $this->assertEquals(1, $article->reactions()->count());
        $this->assertEquals('clap', $article->reactions()->first()->type);
    }

    /** @test */
    public function it_can_remove_reaction_with_toggle()
    {
        $profile = $this->createProfile();

        $article = $this->createArticle();

        $profile->reactTo($article, 'like');

        $profile->toggleReactionOn($article, 'like');

        $this->assertEquals(0, $article->reactions()->count());
    }

    /** @test */
    public function it_can_check_if_reacted_on_reactable_model()
    {
        $profile1 = $this->createProfile();

        $article = $this->createArticle();

        $profile1->reactTo($article, 'like');

        $this->assertTrue($profile1->isReactedOn($article));

        $profile2 = $this->createProfile();

        $this->assertFalse($profile2->isReactedOn($article));
    }

    /** @test **/
    public function it_can_have_reacted_reaction_on_reactable_model()
    {
        $profile = $this->createProfile();

        $article = $this->createArticle();

        $profile->reactTo($article, 'like');

        $this->assertInstanceOf(Reaction::class, $profile->reactedOn($article));
        $this->assertEquals('like', $profile->reactedOn($article)->type);
    }
}
