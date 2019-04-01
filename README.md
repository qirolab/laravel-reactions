# Laravel Reactions [![GitHub release](https://img.shields.io/github/release/qirolab/laravel-reactions.svg?style=flat-square)](https://github.com/qirolab/laravel-reactions) [![Travis (.org) branch](https://img.shields.io/travis/qirolab/laravel-reactions/master.svg?style=flat-square)](https://github.com/qirolab/laravel-reactions) [![StyleCI](https://github.styleci.io/repos/140428012/shield?branch=master)](https://github.styleci.io/repos/140428012) [![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/qirolab/laravel-reactions)

Laravel reactions package for implementing reactions (eg: like, dislike, love, emotion etc) on Eloquent models.

## Installation

Download package into the project using Composer.

```bash
$ composer require qirolab/laravel-reactions
```

### Registering package
> Laravel 5.5 (or higher) uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

For Laravel 5.4 or earlier releases version include the service provider within `app/config/app.php`:

```php
'providers' => [
    Qirolab\Laravel\Reactions\ReactionsServiceProvider::class,
],
```

### Database Migration
If you want to make changes in migrations, publish them to your application first.

```bash
$ php artisan vendor:publish --provider="Qirolab\Laravel\Reactions\ReactionsServiceProvider" --tag=migrations
```

Run database migrations.
```bash
$ php artisan migrate
```

## Usage

### Prepare Reacts (User) Model
Use `Qirolab\Laravel\Reactions\Contracts\ReactsInterface` contract in model which will perform react behavior on reactable model and implement it and use `Qirolab\Laravel\Reactions\Traits\Reacts` trait.

```php
use Qirolab\Laravel\Reactions\Traits\Reacts;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ReactsInterface
{
    use Reacts;
}
```

### Prepare Reactable Model

Use `Qirolab\Laravel\Reactions\Contracts\ReactableInterface` contract in model which will get reaction behavior and implement it and use `Qirolab\Laravel\Reactions\Traits\Reactable` trait.

```php
use Illuminate\Database\Eloquent\Model;
use Qirolab\Laravel\Reactions\Traits\Reactable;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;

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
    "like" => 5,
    "dislike" => 2,
    "clap" => 4,
    "hooray" => 1
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

### Reaction on Model
```php
// It will return the Reaction object that is reacted by given user.
$article->reacted($user);
$article->reacted(); // current login user
$article->reacted; // current login user

$user->reactedOn($article);
```

### Events

On each reaction added `\Qirolab\Laravel\Reactions\Events\OnReaction` event is fired.

On each reaction removed `\Qirolab\Laravel\Reactions\Events\OnDeleteReaction` event is fired.

### Testing

Run the tests with:

```bash
$ vendor/bin/phpunit
```