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

$theme_dir = plugin_dir_path( __FILE__ );
if ( ! defined( 'WPPQ_FILE' ) ) {
	define( 'WPPQ_FILE', __FILE__ );
}
if ( ! defined( 'PLUGIN_DIR' ) ) {
	define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'PLUGIN_URL' ) ) {
	define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}


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
require $theme_dir . 'src/admin/importquiz.php'; 
require $theme_dir . 'src/api/addanswers.php';

/**
 * Add CSS JS to edit  edit post in admin
 * Action admin_enqueue_scripts
 */
function add_admin_scripts( $hook ) {
    global $post;
    $options = get_option( 'wpqp_fields_options' );
    $screens =   (isset($options['content_type'] )) ? $options['content_type'] : array(); 
    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if (    in_array($post->post_type, $screens ) ) {     
            //Style
            wp_enqueue_style( 'sqp-bootstrap', BOOTSTRAP_CSS, array(  ), false, true );
            wp_enqueue_style( 'sqp-admin-style', PLUGIN_URL . 'css/admin.css',array('sqp-bootstrap') );
            wp_enqueue_style( 'prism-style', PLUGIN_URL . 'css/prism.css',array() );
            //JS
			wp_enqueue_script( 'sqp-bootstrap-js', BOOTSTRAP_JS , array( 'jquery' ), false, true);
			wp_enqueue_script( 'vuejs', VUEJS , array( 'jquery' ) , false, true );
			wp_enqueue_script( 'axios', AXIOS , array( 'vuejs' ) , false, true );
			wp_enqueue_script('prism', PLUGIN_URL . 'js/prism.js',array(   ), false, true ); 
			wp_enqueue_script('wp_quiz_post_script', PLUGIN_URL . 'js/script.js',array( 'vuejs','axios','prism' ), false, true ); 
            
        }
    }
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );

function wpqp_post_shortcode() {
    $message = 'Hello world!';
    return $message;
}
add_shortcode('wp_quiz_post', 'wpqp_post_shortcode');

function wpqp_post_load_theme($post_id){
    $options = get_option( 'wpqp_fields_options' );
    $post   = get_post( $post_id );
    $qs =  get_post_meta( $post_id, FIELD_THEME, true );
 
    if(isset($qs) &&  $qs == "list")
      
      wpqp_list_theme($post_id);
    else
      wpqp_default_theme($post_id);
     
}