# Extended Post Status

A library which provides extended functionality to WordPress post type statuses.

## Example

```php
<?php

// Register `archive` status.
register_extended_post_status( 'archive', [
	'post_type' => 'post'
], [
	'singular' => 'Archived'
] );

// Register `obsolete` status.
register_extended_post_status( 'obsolete', [
	'post_type' => 'post'
], [
	'singular' => 'Obsolete'
] );
```

# License

MIT © [Fredrik Forsmo](https://github.com/frozzare)
