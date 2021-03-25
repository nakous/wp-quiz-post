<?php
function wpqp_question_meta_box() {
    $options = get_option( 'wpqp_fields_options' );
    $screens = (isset($options['content_type'] )) ? $options['content_type'] : array();
    foreach ( $screens as $screen ) {
        add_meta_box(
            'wpqp-questions',
            'Questions',
            'wpqp_questions_meta_box_callback',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', 'wpqp_question_meta_box' );
function wpqp_questions_meta_box_callback( $post ) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'global_notice_nonce', 'global_notice_nonce' );
    $questions = get_post_meta( $post->ID, FIELD_QUESTIONS, true );
    $answers = get_post_meta( $post->ID, FIELD_ANSWERS, true );
    echo '<textarea style="width:100%" id="'.FIELD_QUESTIONS.'" name="'.FIELD_QUESTIONS.'">' . esc_attr( $questions ) . '</textarea>';
    echo '<textarea style="width:100%" id="'.FIELD_ANSWERS.'" name="'.FIELD_ANSWERS.'">' . esc_attr( $answers ) . '</textarea>';
   echo quiz_html( $post );
}
function save_wpqp_questions_meta_box_data( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['global_notice_nonce'] ) ) {
        return;
    }
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['global_notice_nonce'], 'global_notice_nonce' ) ) {
        return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    }
    else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    /* OK, it's safe for us to save the data now. */
    // Make sure that it is set.
    if ( ! isset( $_POST[FIELD_QUESTIONS] ) ) {
        return;
    }
    // Sanitize user input.
    $a_data =   $_POST[FIELD_ANSWERS]  ;
    update_post_meta( $post_id, FIELD_ANSWERS, $a_data );
    
    // Update the meta field in the database.
    $q_data =   $_POST[FIELD_QUESTIONS] ;
    update_post_meta( $post_id, FIELD_QUESTIONS, $q_data );
    
   
    
}
add_action( 'save_post', 'save_wpqp_questions_meta_box_data' );