<?php
define("NAME",     "wp-quiz-post");
define("TYPE",     "quiz");
define("SITE_URL",     get_site_url());
define("FIELD_QUESTIONS",     "wpqp_post_question");
define("FIELD_ANSWERS",     "wpqp_post_answers");
define("VUEJS","https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js");
define("AXIOS","https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js");
define("BOOTSTRAP_JS","https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js");
define("BOOTSTRAP_CSS","https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css");

add_action( 'init', 'wpqp_add_new_image_size' );
function wpqp_add_new_image_size() {
    add_image_size( 'wpqp_quiz', 120, 120, true ); //mobile
}