<?php

namespace Qirolab\Tests\Laravel\Reactions\Unit;

use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Tests\Laravel\Reactions\TestCase;

class ReactionsTableConfigTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $app['config']->set('reactions.table_name', 'test-reactions');
    }

    /** @test **/
    public function it_may_change_reactions_table_from_configuration()
    {
        $this->assertEquals(
            config()->get('reactions.table_name'),
            'test-reactions'
        );

        $this->assertEquals(
            (new Reaction())->getTable(),
            'test-reactions'
        );

        $article = $this->createArticle();
        $user = $this->createUser();
        $this->actingAs($user);
        $article->react('like');

        $this->assertEquals(1, $article->reactions->count());
        $this->assertEquals($user->id, $article->reactions->first()->user_id);
    }
}
