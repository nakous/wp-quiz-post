<?php

/**
 * Page Options
 */
function wpqp_options_page()
{
 //echo "wpqp_options_page";
 
?>
  <div class="wrap">
<form action="options.php" method="post">
        <?php 
        settings_fields( 'wpqp_fields_options' );
        do_settings_sections( 'wpqp_fields_sections' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    </div>
<?php
}