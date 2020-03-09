# Changelog

All notable changes to `laravel-reactions` will be documented in this file

## 2.4.0 - 2020-03-09
- Laravel 7.0 support

## 2.3.0 - 2019-09-18
- Laravel 6.0 support

## 2.2.0 - 2019-04-01

### Fixed
- Changed output for `reactionSummary()` and 'reaction_summary' method in reactable model.
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
- Fixed `toggleReaction()` function. Now it will return `Qirolab\Laravel\Reactions\Models\Reaction` object.

### Added
- new `reacted()` method added on in reactable model.
```php
$article->reacted(); // current login user
```
- new `reactedOn($article)` method added to reacts model.
```php
$user->reactedOn($article);
```

## 2.1.0 - 2019-03-20

- Laravel 5.8 compatibility

## 2.0.0 - 2018-12-31

- package namespace changed from Hkp22 to Qirolab.
- package vendor name changed from hkp22 to qirolab. Now to install this package required new command to run `composer require qirolab/laravel-reactions`.


## 1.0.0 - 2018-07-19

- initial release

