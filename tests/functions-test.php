<?php

class Extended_Post_Status_Functions_Test extends WP_UnitTestCase {

    public function test_register_oldf_post_status() {
        $post_status_object = get_post_status_object( 'oldf' );
        $this->assertNull( $post_status_object );

        register_extended_post_status( 'oldf' );
        $post_status_object = get_post_status_object( 'oldf' );
        $this->assertSame( 'oldf', $post_status_object->label );
    }

    public function test_register_carf_post_status() {
        $post_status_object = get_post_status_object( 'carf' );
        $this->assertNull( $post_status_object );

        register_extended_post_status( 'carf', [
            'label' => 'Car'
        ] );
        $post_status_object = get_post_status_object( 'carf' );
        $this->assertSame( 'Car', $post_status_object->label );
    }
}
