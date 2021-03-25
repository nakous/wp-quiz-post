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