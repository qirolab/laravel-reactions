# Laravel Reactions [![GitHub release](https://img.shields.io/github/release/hkp22/laravel-reactions.svg?style=flat-square)](https://github.com/hkp22/laravel-reactions) [![Travis (.org) branch](https://img.shields.io/travis/hkp22/laravel-reactions/master.svg?style=flat-square)](https://github.com/hkp22/laravel-reactions) [![StyleCI](https://github.styleci.io/repos/140428012/shield?branch=master)](https://github.styleci.io/repos/140428012) [![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/hkp22/laravel-reactions)

Laravel reactions package for implementing reactions (eg: like, dislike, love, emotion etc) on Eloquent models.

## Installation

Download package into the project using Composer.

```bash
$ composer require hkp22/laravel-reactions
```

### Registering package
> Laravel 5.5 (or higher) uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

For Laravel 5.4 or earlier releases version include the service provider within `app/config/app.php`:

```php
'providers' => [
    Hkp22\Laravel\Reactions\ReactionsServiceProvider::class,
],
```

### Database Migration
If you want to make changes in migrations, publish them to your application first.

```bash
$ php artisan vendor:publish --provider="Hkp22\Laravel\Reactions\ReactionsServiceProvider" --tag=migrations
```

Run database migrations.
```bash
$ php artisan migrate
```

## Usage

### Prepare Reacts (User) Model
Use `Hkp22\Laravel\Reactions\Contracts\ReactsInterface` contract in model which will perform react behavior on reactable model and implement it and use `Hkp22\Laravel\Reactions\Traits\Reacts` trait.

```php
use Hkp22\Laravel\Reactions\Traits\Reacts;
use Hkp22\Laravel\Reactions\Contracts\ReactsInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ReactsInterface
{
    use Reacts;
}
```

### Prepare Reactable Model

Use `Hkp22\Laravel\Reactions\Contracts\ReactableInterface` contract in model which will get reaction behavior and implement it and use `Hkp22\Laravel\Reactions\Traits\Reactable` trait.

```php
use Illuminate\Database\Eloquent\Model;
use Hkp22\Laravel\Reactions\Traits\Reactable;
use Hkp22\Laravel\Reactions\Contracts\ReactableInterface;

class Article extends Model implements ReactableInterface
{
    use Reactable;
}
```

## Available Methods

### Reaction
```php
$user->reactTo($article, 'like');

$article->react('like'); // current login user
$article->react('like', $user);
```

### Remove Reaction
Removing reaction of user from reactable model.
```php
$user->removeReactionFrom($article);

$article->removeReaction(); // current login user
$article->removeReaction($user);
```

### Toggle Reaction
The toggle reaction method will add a reaction to the model if the user has not reacted. If a user has already reacted, then it will replace the previous reaction with a new reaction. For example, if the user has reacted 'like' on the model. Now on toggles reaction to 'dislike' then it will remove the 'like' and stores the 'dislike' reaction.

If a user has reacted `like` then on toggle reaction with `like`. It will remove the reaction.

```php
$user->toggleReactionOn($article, 'like');

$article->toggleReaction('like'); // current login user
$article->toggleReaction('like', $user);
```

### Boolean check if user reacted on model

```php
$user->isReactedOn($article));

$article->is_reacted; // current login user
$article->isReactBy(); // current login user
$article->isReactBy($user);
```

### Reaction summery on model
```php
$article->reactionSummary();
$article->reaction_summary;

// example
$article->reaction_summary->toArray();
// output
/*
[
    ['type' => 'clap', 'count' => '4'],
    ['type' => 'dislike', 'count' => '2'],
    ['type' => 'hooray', 'count' => '1'],
    ['type' => 'like', 'count' => '5']
]
*/
```

### Get collection of users who reacted on model
```php
$article->reactionsBy();
```

### Scopes
Find all articles reacted by user.
```php
Article::whereReactedBy()->get(); // current login user

Article::whereReactedBy($user)->get();
Article::whereReactedBy($user->id)->get();
```

### Events

On each reaction added `\Hkp22\Laravel\Reactions\Events\OnReaction` event is fired.

On each reaction removed `\Hkp22\Laravel\Reactions\Events\OnDeleteReaction` event is fired.

### Testing

Run the tests with:

```bash
$ vendor/bin/phpunit
```