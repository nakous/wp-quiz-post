<?php
/*
*  Instalation
*/
function wpqp_database_table()
{
    global $table_prefix, $wpdb;
    $tblname = 'quiz_answers';
    $wp_track_table = $table_prefix . $tblname ;
    #Check to see if the table exists already, if not, then create it
    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table)
    {
        $sql = "    CREATE TABLE  `". $wp_track_table . "` (";
        $sql .= "    `id` int(11) NOT NULL auto_increment,";
        $sql .= "    `mail` varchar(255) NOT NULL,";
        $sql .= "    `name` varchar(255) DEFAULT NULL,";
        $sql .= "    `postid` int(11) NOT NULL,";
        $sql .= "    `uid` int(11) DEFAULT NULL,";
        $sql .= "    `answer` text NOT NULL,";
        $sql .= "    `score` int(11) DEFAULT NULL,";
        $sql .= "    `percentage` int(11) DEFAULT NULL,";
        $sql .= "    `notif` int(11) NOT NULL DEFAULT '0',"; 
        $sql .= "    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,";
        $sql .= "    PRIMARY KEY (`id`)";
        $sql .= "  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        require_once( ABSPATH   . '/wp-admin/includes/upgrade.php' );
         dbDelta($sql);
        // add_option( "wpqp_db_version", "111" );
         wpqp_manage_option("111");
    }
}
function wpqp_remove_database() {
      global $wpdb;
      $table_name = $wpdb->prefix . 'quiz_answers';
      $sql = "DROP TABLE IF EXISTS $table_name";
      $wpdb->query($sql);
      delete_option("wpqp_fields_options");
}
register_activation_hook( WPPQ_FILE, 'wpqp_database_table' );
register_deactivation_hook( WPPQ_FILE, 'wpqp_remove_database' );

/**
 * Update 1.1.2
 * 03/30/2021
 * add answer status
 * create table import quiz
 */
function update_112(){
    global $wpdb ;

    $table_name = $wpdb->prefix . 'quiz_answers';

    $wpdb->query(
        "ALTER TABLE $table_name
         ADD COLUMN `status` varchar(50) DEFAULT NULL,
         ADD COLUMN `ip` varchar(220) DEFAULT NULL
        ");

    //Create table import
    $tblname = 'wpqp_quiz_import';
    $wp_track_table = $wpdb->prefix . $tblname ;
    #Check to see if the table exists already, if not, then create it
    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table)
    {
        $sql = "    CREATE TABLE  `". $wp_track_table . "` (";
        $sql .= "    `id` int(11) NOT NULL auto_increment,";
        $sql .= "    `status` varchar(255) NOT NULL,";
        $sql .= "    `title` varchar(255) DEFAULT NULL,";
        $sql .= "    `postid` int(11) NOT NULL,";
        $sql .= "    `uid` int(11) DEFAULT NULL,";
        $sql .= "    `file` varchar(255) DEFAULT NULL,";
        $sql .= "    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,";
        $sql .= "    PRIMARY KEY (`id`)";
        $sql .= "  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        require_once( ABSPATH   . '/wp-admin/includes/upgrade.php' );
         dbDelta($sql);
    }

    // add_option( "wpqp_db_version", "112" );
    wpqp_manage_option("112");

}

function plugin_update() {
    
    $wpqp_db_version = get_option('wpqp_db_version', '111');
    if($wpqp_db_version < '112') {
        update_112();
    }


}

add_action( 'plugins_loaded', 'plugin_update' );

function wpqp_manage_option($v){
    $option_name = 'wpqp_db_version' ;
    if ( get_option( $option_name ) !== false ) {
    
        update_option( $option_name, $v );
    } else {
        add_option( $option_name, $v  );
    }
}

