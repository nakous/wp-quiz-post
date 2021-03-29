<?php
/**
 * List Theme
 */
function wp_api_form_answers_endpoints() {
  register_rest_route( 'quiz/apiv1', '/formanswers', array(
        'methods' => 'POST',
        'callback' => 'formanswers_callback',
        'permission_callback'  => function () {
            return true;
          },
    ));
}
add_action( 'rest_api_init', 'wp_api_form_answers_endpoints' );
function formanswers_callback(WP_REST_Request $req) {
  try {
   
    $param = $req->get_params();
  //  return $param;
  if(!isset($param["choose"]) || count($param["choose"]) == 0)
    return array('errer' => true,'message' => 'Please answer to the suestion before submit!');

   $score = 0;
   $percentage = 0;
    global $wpdb;     
    $table_name = $wpdb->prefix . 'quiz_answers';     
       $answers = json_decode(get_post_meta( $param["post"], FIELD_ANSWERS, true ), true);
        $user_answers = $param["choose"];
        foreach($answers as $key =>$val ){
            if(isset($user_answers[$key]) && $user_answers[$key] == $val)
               $score++;
        }
        
       if(count($answers) !=0 )
           $percentage = intval (($score / count($answers)) * 100 );
    $wpdb->insert($table_name, array(
      'mail' => $param["mail"], 
      'name' => $param["name"], 
      'postid' => $param["post"], 
      'answer' => json_encode($param["choose"]), 
      'score' => $score, 
      'percentage' =>$percentage
      )); 
                 
    return array(
    'score' => $score,
    'percentage' => $percentage,
    'total' =>count($answers)
  );
  } catch (Exception $e) {
    return array('errer' => true,'message' => $e);
}
}
/**
 * Default theme
 */
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

  try {
   

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
    } catch (Exception $e) {
      return array('errer' => true,'message' => 'message');
  }
}
add_action( 'rest_api_init', 'wp_api_add_answers_endpoints' );