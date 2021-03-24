
<?php

function addWPQPAdminMenu() {
//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    add_menu_page(  NAME,
        'Wp Quiz Page',
        'administrator',
        NAME,
         'WPQPAdminDashboard' ,
        'dashicons-chart-area',  //plugin_dir_url( __FILE__ ) . 'img/logo.png'
        26 
    ); 
    add_submenu_page( NAME,
        'WPQP Settings',
        'Settings',
        'administrator',
        NAME.'-settings',
       'wpqp_options_page' 
    );
    add_submenu_page( NAME,
        'Answers List',
        'Answers List',
        'administrator',
        NAME.'-answers',
         'wpqp_Answers_list' 
    );
}
add_action('admin_menu',  'addWPQPAdminMenu' , 9);  