# Extended Post Status

> Requires PHP 5.5.9

A library which provides extended functionality to WordPress post type statuses.

## Installation

```sh
composer require frozzare/wp-extended-post-status
```

## Example

```php
<?php

// Register `archive` status.
register_extended_post_status( 'archive', [], [
  'singular' => 'Archived'
] );

// Register `obsolete` status.
register_extended_post_status( 'obsolete', [
  // Which post type the post status should be registered on, array or string of post types.
  'post_type' => ['post', 'page']
], [
  'singular' => 'Obsolete'
] );
```

# License

MIT © [Fredrik Forsmo](https://github.com/frozzare)
