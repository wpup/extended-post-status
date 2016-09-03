<?php

class Extended_Post_Status_Test extends WP_UnitTestCase {

    public function test_register_old_post_status() {
        $post_status_object = get_post_status_object( 'old' );
        $this->assertNull( $post_status_object );

        new Extended_Post_Status( 'old' );
        $post_status_object = get_post_status_object( 'old' );
        $this->assertSame( 'old', $post_status_object->label );
    }

    public function test_register_car_post_status() {
        $post_status_object = get_post_status_object( 'car' );
        $this->assertNull( $post_status_object );

        new Extended_Post_Status( 'car', [
            'label' => 'Car'
        ] );
        $post_status_object = get_post_status_object( 'car' );
        $this->assertSame( 'Car', $post_status_object->label );
    }

    public function test_post_states_bad_post_object() {
        $class = new Extended_Post_Status( 'car', [
            'label' => 'Car'
        ] );

        $this->assertSame( [], $class->post_states( [], null ) );
    }

    public function test_post_states_wrong_post_type() {
        $class = new Extended_Post_Status( 'car', [
            'label' => 'Car'
        ] );

        $post_id = $this->factory->post->create( ['post_type'=>'page'] );
        $this->assertEmpty( $class->post_states( [], get_post( $post_id ) ) );
    }

    public function test_post_states_wrong_post_status() {
        $class = new Extended_Post_Status( 'car', [
            'label' => 'Car'
        ] );

        $post_id = $this->factory->post->create();
        $this->assertEmpty( $class->post_states( [], get_post( $post_id ) ) );
    }

    public function test_post_states_success() {
        $class = new Extended_Post_Status( 'car', [
            'label' => 'Car'
        ] );

        $post_id = $this->factory->post->create( ['post_status' => 'car'] );
        $this->assertSame( [
            'car' => 'Car'
        ], $class->post_states( [], get_post( $post_id ) ) );
    }
}
