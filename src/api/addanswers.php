<?php
function wp_api_add_answers_endpoints() {
  register_rest_route( 'quiz/apiv1', '/addanswers', array(
        'methods' => 'POST',
        'callback' => 'addAnswers_callback',
        'permission_callback'  => function () {
            return true;
          },
    ));
}
function addAnswers_callback(WP_REST_Request $req) {
  		$body = $req->get_body();
		 $bd = json_decode($body, TRUE);
		 $score = 0;
		 $percentage = 0;
		  global $wpdb;     
		  $table_name = $wpdb->prefix . 'quiz_answers';     
         $answers = json_decode(get_post_meta( $bd["post"], FIELD_ANSWERS, true ), true);
          $user_answers = json_decode($bd["answers"], true);
          foreach($answers as $key =>$val ){
              if(isset($user_answers[$key]) && $user_answers[$key] == $val)
                 $score++;
          }
         //$result = array_intersect($answers, $user_answers);
          //$score = count($answers) - count($result) ;
         if(count($answers) !=0 )
             $percentage = intval (($score / count($answers)) * 100 );
		  $wpdb->insert($table_name, array(
			  'mail' => $bd["user"]["mail"], 
			  'name' =>$bd["user"]["name"], 
			  'postid' => $bd["post"], 
			  'answer' => $bd["answers"], 
			  'score' => $score, 
			  'percentage' =>$percentage
			  )); 
								   
  		return array('score' => $score,'percentage' => $percentage);
}
add_action( 'rest_api_init', 'wp_api_add_answers_endpoints' );