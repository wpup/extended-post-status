<?php

final class Extended_Post_Status {

    /**
     * The post status arguments plus some extra arguments.
     *
     * The arguments listed are the ones from `register_post_status`
     * and some have different default values:
     *
     * - `public` has `true` as default value instead of `false`
     * - `show_in_admin_status_list` has `true` as default value instead of `false`
     *
     * @var array
     */
    protected $args = [
        'exclude_from_search'       => false,
        'internal'                  => false,
        'label'                     => '',
        'label_count'               => '',
        'post_type'                 => ['post'], // Custom arg
        'private'                   => false,
        'protected'                 => false,
        'public'                    => true,
        'publicly_queryable'        => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true
    ];

    /**
     * The post status names.
     *
     * @var array
     */
    protected $names = [
        'singular' => '',
        'plural'   => ''
    ];

    /**
     * The post status.
     *
     * @var string
     */
    protected $status = '';

    /**
     * Constructor.
     *
     * @param string $status
     * @param array  $args
     * @param array  $names
     */
    public function __construct( $status, array $args = [], array $names = [] ) {
        $this->args = array_merge( $this->args, $args );
        $this->names = array_merge( $this->names, $names );
        $this->status = sanitize_key( $status );

        // Set post status name or label as singular name if singular name if empty.
        if ( empty( $this->names['singular'] ) ) {
            if ( empty( $this->args['label'] ) ) {
                $this->names['singular'] = $this->status;
            } else {
                $this->names['singular'] = $this->args['label'];
            }
        }

        // Set singular name as plural name if plural name if empty.
        if ( empty( $this->names['plural'] ) ) {
            $this->names['plural'] = $this->names['singular'] . 's';
        }

        $this->setup_actions();
        $this->setup_filters();
        $this->register();
    }

    /**
     * Output edit screen JavaScript.
     */
    public function edit_screen_js() {
        global $post;

        // Only valid post types.
        if ( ! $this->valid_post_type( $post->post_type ) ) {
            return;
        }

        ?>
        <script>
        jQuery(function($) {
            $('select[name="_status"]')
                .append('<option value="<?php echo esc_attr( $this->status ); ?>"><?php echo esc_html( $this->names['singular'] ); ?></option>');

            $('.editinline').on('click', function() {
                var $row      = $(this).closest('tr'),
                    $option   = $('.inline-edit-row').find('select[name="_status"] option[value="<?php echo esc_attr( $this->status ); ?>"]'),
                    hasStatus = $row.hasClass('status-<?php echo esc_attr( $this->status ); ?>');

                $option.prop('selected', hasStatus);
            });
        });
        </script>
        <?php
    }

    /**
     * Get label count template.
     *
     * @return array
     */
    protected function get_label_count() {
        $plural   = esc_html( $this->names['plural'] );
        $singular = esc_html( $this->names['singular'] );
        $text     = ' <span class="count">(%s)</span>';

        // @codingStandardsIgnoreStart
        return _n_noop(
            $singular . $text,
            $plural . $text,
            'extended-post-status-' . $this->status
        );
        // codingStandardsIgnoreEnd
    }

    /**
     * Get post type that should have the post status.
     *
     * @return array
     */
    protected function get_post_type() {
        $post_type = $this->args['post_type'];
        $post_type = is_string( $post_type ) ? [$post_type] : $post_type;

        // Add `auto-draft` post type if missing.
        if ( ! in_array( 'auto-draft', $post_type ) ) {
            $post_type[] = 'auto-draft';
        }

        return $post_type;
    }

    /**
     * Register post status.
     */
    protected function register() {
        $args = $this->args;

        // Set label if empty.
        if ( empty( $args['label'] ) ) {
            $args['label'] = $this->status;
        }

        // Set label count if empty.
        if ( empty( $args['label_count'] ) ) {
            $args['label_count'] = $this->get_label_count();
        }

        register_post_status( $this->status, $args );
    }

    /**
     * Output post screen JavaScript.
     */
    public function post_screen_js() {
        global $post;

        // Only valid post types.
        if ( ! $this->valid_post_type( $post->post_type ) ) {
            return;
        }

        // Add post status to the list.
        ?>
        <script>
        jQuery(function($) {
            $('#post_status')
                .append('<option value="<?php echo esc_attr( $this->status ); ?>"><?php echo esc_html( $this->names['singular'] ); ?></option>');

            var btnText = '<?php echo str_replace( __( 'Pending' ), '', __( 'Save as Pending' ) ); ?>' +
                '<?php esc_html_e( $this->names['singular'] ); ?>';

            <?php if ( $post->post_status === $this->status ): ?>
                $('#save-post').val(btnText);
            <?php endif; ?>

            $('.save-post-status').on('click', function () {
                if ($('#post_status').val() !== '<?php echo $this->status; ?>') {
                    return;
                }

                $('#save-post').val(btnText);
            })
        });
        </script>
        <?php

        // Set the right current post status.
        if ( $post->post_status === $this->status ) {
            ?>
            <script>
            jQuery(function($) {
                $('#post-status-display')
                    .text('<?php echo esc_html( $this->names['singular'] ); ?>');

                $('#post_status')
                    .val('<?php echo esc_html( $this->status ); ?>');
            });
            </script>
            <?php
        }
    }

    /**
     * Add custom post state text for custom post status.
     *
     * @param  array   $post_states
     * @param  WP_Post $post
     *
     * @return array
     */
    public function post_states( array $post_states, $post ) {
        // Only valid post types.
        if ( ! $this->valid_post_type( $post->post_type ) ) {
            return $post_states;
        }

        // Only valid status.
        if ( $post->post_status !== $this->status || $this->status === get_query_var( 'post_status' ) ) {
            return $post_states;
        }

        return array_merge( $post_states, [
            $this->status => $this->names['singular']
        ] );
    }

    /**
     * Setup action hooks.
     */
    protected function setup_actions() {
        add_action( 'admin_footer-edit.php', [$this, 'edit_screen_js'] );
        add_action( 'admin_footer-post.php', [$this, 'post_screen_js'] );
        add_action( 'admin_footer-post-new.php', [$this, 'post_screen_js'] );
    }

    /**
     * Setup filter hooks.
     */
    protected function setup_filters() {
        add_filter( 'display_post_states', [$this, 'post_states'], 10, 2 );
    }

    /**
     * Check if post type is valid or not.
     *
     * @param  string $post_type
     *
     * @return bool
     */
    protected function valid_post_type( $post_type ) {
		$post_types = $this->get_post_type();

		if ( in_array( 'any', $post_types ) ) {
			return true;
		}

		return in_array( $post_type, $post_types );
    }
}
