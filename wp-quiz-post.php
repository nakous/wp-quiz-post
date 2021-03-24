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


$theme_dir = plugin_dir_path( __FILE__ ) ;

require $theme_dir . 'config.php';
require $theme_dir . 'install.php';

require $theme_dir . 'template/theme.default.php';
require $theme_dir . 'template/theme.list.php';

require $theme_dir . 'src/admin/metabox.php';
require $theme_dir . 'src/admin/htmlbox.php';
require $theme_dir . 'src/admin/menu.php';
require $theme_dir . 'src/admin/fields.php';
require $theme_dir . 'src/admin/admindashboard.php';
require $theme_dir . 'src/admin/settingspage.php';
require $theme_dir . 'src/admin/answerslist.php'; 

require $theme_dir . 'src/api/addanswers.php';

/**
 * Add CSS JS to edit  edit post in admin
 * Action admin_enqueue_scripts
 */

function add_admin_scripts( $hook ) {

    global $post;
    $options = get_option( 'wpqp_fields_options' );
    $screens = $options['content_type'] ;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if (    in_array($post->post_type, $screens ) ) {     
            //Style
            wp_enqueue_style( 'sqp-bootstrap', BOOTSTRAP_CSS, array(  ), false, true );
            wp_enqueue_style( 'sqp-admin-style', plugin_dir_url(__FILE__) . 'css/admin.css',array('sqp-bootstrap') );
            
            //JS

			wp_enqueue_script( 'sqp-bootstrap-js', BOOTSTRAP_JS , array( 'jquery' ), false, true);
			wp_enqueue_script( 'vuejs', VUEJS , array( 'jquery' ) , false, true );
			wp_enqueue_script( 'axios', AXIOS , array( 'vuejs' ) , false, true );
			wp_enqueue_script('wp_quiz_post_script', plugin_dir_url(__FILE__) . 'js/script.js',array( 'vuejs','axios' ), false, true ); 
        }
    }
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );


 
function wpqp_post_shortcode() { 
 
 
    $message = 'Hello world!'; 
     
    
    return $message;
} 
 
add_shortcode('wp_quiz_post', 'wpqp_post_shortcode'); 
