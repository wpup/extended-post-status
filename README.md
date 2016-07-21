# Extended Post Status [![Build Status](https://travis-ci.org/frozzare/wp-extended-post-status.svg?branch=master)](https://travis-ci.org/frozzare/wp-extended-post-status)

> Requires PHP 5.5.9

A library which provides extended functionality to WordPress post type statuses.

## Installation

```sh
composer require frozzare/wp-extended-post-status
```

## Example

```php
<?php

// Register `old` status.
register_extended_post_status( 'old', [], [
  'singular' => 'Old'
] );

// Register `obsolete` status.
register_extended_post_status( 'obsolete', [
  // Array or string of post types where post status should be registered
  'post_type' => ['post', 'page']
], [
  'singular' => 'Obsolete'
] );
```

## Documentation

```php
function register_extended_post_status( string $post_status, array $args = [], array $names = [] )
```

* `$post_status` is the name of the post status.
* `$args` is a array of arguments for this post status, the same as for `register_post_status` plus `post_type` argument, which should be array or string of post types where post status should be registered.
* `$names` is a array of `singular` and `plural` that is used as names for `label_count` if empty.

Differences from `register_post_status` arguments:

* `public` has `true` as default value instead of `false`
* `show_in_admin_status_list` has `true` as default value instead of `false`

# License

MIT © [Fredrik Forsmo](https://github.com/frozzare)
