<?php
 
/**
 
 * @package WP Quiz Post
 
 */
 
/*
 
Plugin Name: WP Quiz Post
 
Plugin URI: https://github.com/nakous/wp-quiz-post
 
Description: WP Quiz plugin for Post
 
Version: 1.1.1
 
Author: Nakous
 
Author URI: https://profiles.wordpress.org/nakous/
 
License: GPLv2 or later
 
Text Domain: wp-quiz-post
 
*/

 
define("TYPE",     "quiz");
define("FIELD_QUESTIONS",     "question");
define("FIELD_ANSWERS",     "answers");
define("VUEJS","https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js");
define("AXIOS","https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js");

define("BOOTSTRAP_JS","https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js");
define("BOOTSTRAP_CSS","https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css");

function add_admin_scripts( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( TYPE === $post->post_type ) {     
            //Style
            wp_enqueue_style( 'sqp-bootstrap', BOOTSTRAP_CSS, array(  ), false, true );
            wp_enqueue_style( 'sqp-admin-style', plugin_dir_url(__FILE__) . '/css/admin.css',array('sqp-bootstrap') );
            
            //JS

			wp_enqueue_script( 'sqp-bootstrap-js', BOOTSTRAP_JS , array( 'jquery' ), false, true);
			wp_enqueue_script( 'vuejs', VUEJS , array( 'jquery' ) , false, true );
			wp_enqueue_script( 'axios', AXIOS , array( 'vuejs' ) , false, true );
			wp_enqueue_script('wp_quiz_post_script', plugin_dir_url(__FILE__) . '/js/script.js',array( 'vuejs','axios' ), false, true ); 
        }
    }
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );



abstract class WPQP_Meta_Box {
 
 
    /**
     * Set up and add the meta box.
     */
    public static function add() {
        $screens = [ TYPE ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'WPQP_box_id',          // Unique ID
                'QUIZ', // Box title
                [ self::class, 'html' ],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }
 
 
    /**
     * Save the meta box selections.
     *
     * @param int $post_id  The post ID.
     */
    public static function save( int $post_id ) {
       /* if ( array_key_exists(FIELD_QUESTIONS, $_POST ) ) {
            update_post_meta(
                $post_id,
                FIELD_QUESTIONS,
                $_POST[FIELD_QUESTIONS]
            );
        }
		if ( array_key_exists(FIELD_ANSWERS, $_POST ) ) {
            update_post_meta(
                $post_id,
                FIELD_ANSWERS,
                $_POST[FIELD_ANSWERS]
            );
        }*/
    }
 
 
    /**
     * Display the meta box HTML to the user.
     *
     * @param \WP_Post $post   Post object.
     */
    public static function html( $post ) {
        $value1 = get_post_meta( $post->ID, FIELD_QUESTIONS, true );
        $value2 = get_post_meta( $post->ID, FIELD_ANSWERS, true );
        ?>
		<script>
			FIELD_QUESTIONS = <?php echo $value1 ;?> ;
			FIELD_ANSWERS = <?php echo $value2 ;?> ;
		</script>
		<div id="app-quiz">
	 
            <button  class=""  v-on:click="Add();">Add Question</button>
            <button  class=""  v-on:click="update_answers(question);">Update answers</button>
		    <div class="questions" v-for="(question, indq) in questions.questions" :key="indq"  >
					 
					<div class="card card-handle">
                         <div class="card-title">
                             <div class="" v-html="question.id + ' - '+ question.body"></div>
                        </div> 
                        <ul class="cart-list-group">
                                    <li class="list-group-item"  v-for="(response, index) in question.answers" :key="index">
                                            <input class="form-check-input" :name="'choose' +question.id"  type="radio" v-model="answers[question.id]"  :value="response.id" :id="'flexCheckDefault' + response.id  ">
                                            <label class="form-check-label" :for="'flexCheckDefault' + response.id  "  v-html="response.body">
                                                
                                            </label>
                                    </li>
                                    
                        </ul>  
                        <button  class=""  v-on:click="delete(question);">Delete</button>
                        <button  class=""  v-on:click="Update(question);">Update</button>
					</div>
			</div>

		</div>
		 
			
        <?php
		 /* <input name="hide-<?php echo FIELD_QUESTIONS ;? >" type="text" id="hide-<?php echo FIELD_QUESTIONS ;? >" value="<?php echo $value1 ;? >" class="postbox" />
		 <input name="hide-<? php echo FIELD_ANSWERS ;? >" type="text" id="hide-<?php echo FIELD_ANSWERS ;?>" value="<?php echo $value2 ;? >" class="postbox" /> */ 
    }
}
 
add_action( 'add_meta_boxes', [ 'WPQP_Meta_Box', 'add' ] );
add_action( 'save_post', [ 'WPQP_Meta_Box', 'save' ] );