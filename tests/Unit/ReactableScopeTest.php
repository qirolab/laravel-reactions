<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Article;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactableScopeTest extends TestCase
{
    /** @test */
    public function it_can_get_where_reacted_by_current_user()
    {
        $user = $this->createUser();

        $this->actingAs($user);

        $articles = $this->createArticle([], 3);

        foreach ($articles as $key => $article) {
            $article->react('like');
        }

        $user = $this->createUser();

        $articles = $this->createArticle([], 2);

        foreach ($articles as $key => $article) {
            $article->react('like', $user);
        }

        $reactedArticles = Article::whereReactedBy()->get();

        $this->assertCount(3, $reactedArticles);
    }

    /** @test */
    public function it_can_get_where_reacted_by_concrete_user()
    {
        $user1 = $this->createUser();

        $articles = $this->createArticle([], 3);

        foreach ($articles as $key => $article) {
            $article->react('like', $user1);
        }

        $user2 = $this->createUser();

        $reactedArticles = Article::whereReactedBy($user1)->get();
        $shouldBeEmpty = Article::whereReactedBy($user2)->get();

        $this->assertCount(3, $reactedArticles);
        $this->assertEmpty($shouldBeEmpty);
    }
}
