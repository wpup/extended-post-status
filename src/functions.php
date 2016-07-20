<?php

/**
 * Register extended post status.
 *
 * @param  string $status
 * @param  array  $args {
 *    Optional. The arguments, same as `register_post_status` and some extra:
 *
 *    @type array|string $post_type
 * }
 * @param  array  $names {
 *    Optional. The plural and singular names.
 *
 *    @type string $plural
 *    @type string $singular
 * }
 *
 * @return Extended_Post_Status
 */
function register_extended_post_status( $status, array $args = [], array $names = [] ) {
	return new Extended_Post_Status( $status, $args, $names );
}
