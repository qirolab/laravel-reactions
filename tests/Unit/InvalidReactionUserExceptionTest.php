<?php

namespace Hkp22\Tests\Laravel\Reactions\Unit;

use Hkp22\Tests\Laravel\Reactions\TestCase;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\User;
use Hkp22\Tests\Laravel\Reactions\Stubs\Models\Article;
use Hkp22\Laravel\Reactions\Exceptions\InvalidReactionUser;

class InvalidReactionUserExceptionTest extends TestCase
{
    /** @test */
    public function it_can_throw_exception_if_not_authenticated_on_react()
    {
        $this->expectException(InvalidReactionUser::class);

        $article = factory(Article::class)->create();

        $article->react('like');
    }

    /** @test */
    public function it_can_throw_exception_if_authenticated_but_passed_integer_on_react()
    {
        $this->expectException(InvalidReactionUser::class);

        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $article->react('like', 434);
    }

    /** @test */
    public function it_can_throw_exception_if_not_authenticated_on_where_liked_by()
    {
        $this->expectException(InvalidReactionUser::class);

        Article::whereReactedBy();
    }
}
