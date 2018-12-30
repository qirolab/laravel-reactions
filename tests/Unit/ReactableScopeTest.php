<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Tests\Laravel\Reactions\TestCase;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\User;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Article;

class ReactableScopeTest extends TestCase
{
    /** @test */
    public function it_can_get_where_reacted_by_current_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $articles = factory(Article::class, 3)->create();

        foreach ($articles as $key => $article) {
            $article->react('like');
        }

        $user = factory(User::class)->create();

        $articles = factory(Article::class, 2)->create();

        foreach ($articles as $key => $article) {
            $article->react('like', $user);
        }

        $reactedArticles = Article::whereReactedBy()->get();

        $this->assertCount(3, $reactedArticles);
    }

    /** @test */
    public function it_can_get_where_reacted_by_concrete_user()
    {
        $user1 = factory(User::class)->create();

        $articles = factory(Article::class, 3)->create();

        foreach ($articles as $key => $article) {
            $article->react('like', $user1);
        }

        $user2 = factory(User::class)->create();

        $reactedArticles = Article::whereReactedBy($user1)->get();
        $shouldBeEmpty = Article::whereReactedBy($user2)->get();

        $this->assertCount(3, $reactedArticles);
        $this->assertEmpty($shouldBeEmpty);
    }
}
