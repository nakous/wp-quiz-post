<?php


/*
* Admin Sittings
*/

function wpqp_register_settings() {
    register_setting( 'wpqp_fields_options', 'wpqp_fields_options', 'wpqp_fields_options_validate' );
    add_settings_section( 'ct_settings', 'WPQP Settings', 'wpqp_plugin_section_text', 'wpqp_fields_sections' );
    
    add_settings_field( 'wpqp_fields_setting_content_type', 'Content Type', 'wpqp_fields_setting_content_type', 'wpqp_fields_sections', 'ct_settings' );
    add_settings_field( 'wpqp_fields_setting_active', 'Active', 'wpqp_fields_setting_active', 'wpqp_fields_sections', 'ct_settings' );
    add_settings_field( 'wpqp_fields_setting_skills', 'Skills', 'wpqp_fields_setting_skills', 'wpqp_fields_sections', 'ct_settings' );
    add_settings_field( 'wpqp_fields_setting_quiz_types', 'Quiz Types', 'wpqp_fields_setting_quiz_types', 'wpqp_fields_sections', 'ct_settings' );
    add_settings_field( 'wpqp_fields_setting_answer_types', 'Answer Types', 'wpqp_fields_setting_answer_types', 'wpqp_fields_sections', 'ct_settings' );
    add_settings_field( 'wpqp_fields_setting_helper', 'Helper Text', 'wpqp_fields_setting_helper', 'wpqp_fields_sections', 'ct_settings' );
}
add_action( 'admin_init', 'wpqp_register_settings' );

function wpqp_fields_options_validate( $input ) {
   $newinput['content_type'] = $input['content_type']  ;
   $newinput['active'] = $input['active']  ;
   $newinput['skills'] = $input['skills']  ; 
   $newinput['quiz_types'] = $input['quiz_types']  ;
   $newinput['answer_type'] = $input['answer_type']  ; 
   $newinput['help'] = $input['help']  ; 
 
    return $newinput;
}
function wpqp_plugin_section_text() {
    echo '<p>Here you can set all the options  </p>';
}
function wpqp_fields_setting_helper(){
    $options = get_option( 'wpqp_fields_options' );
        wp_enqueue_editor();
?>
            <textarea id="wpqp_fields_setting_helper"
                name="wpqp_fields_options[help]" >
            <?php if(isset($options['help'] )) echo $options['help']  ;?>
            </textarea>
      <script>
            setTimeout(function() {
                tinymce.init({
                selector:   "#wpqp_fields_setting_helper", 
                menubar: false,
                toolbar: 'undo redo | bold italic underline | code'
                });
            console.log("tinymceStart_a");
            }, 200);
      </script>
 
    <?php
}
function wpqp_fields_setting_quiz_types(){
    $options = get_option( 'wpqp_fields_options' );
    $quiz_types =array("quiz"=>"Quiz","text"=>"Text");
    
        foreach ( $quiz_types as  $key => $type ): 
            ?>
            <input   type="checkbox" id="wpqp_fields_setting_quiz_types"
                name="wpqp_fields_options[quiz_types][]"
                value="<?php echo $key; ?>"  
                <?php  if(isset($options['quiz_types'] ))  checked( in_array ($key, $options['quiz_types'] ) ) ; ?>
            >
              <?php echo esc_html( $type ); ?>  <br>
        <?php endforeach; ?>
 
    <?php
}
function wpqp_fields_setting_answer_types(){
    $options = get_option( 'wpqp_fields_options' );
    $quiz_types =array("radio"=>"Radio","checkbox"=>"Checkbox");
    
        foreach ( $quiz_types as $key =>  $type ): 
            ?>
            <input   type="checkbox" id="wpqp_fields_setting_answer_types"
                name="wpqp_fields_options[answer_type][]"
                value="<?php echo $key; ?>" 
                <?php if(isset($options['answer_type'] ))  checked( in_array ($key, $options['answer_type'] ) ) ; ?>
            >
            <?php echo esc_html( $type ); ?>  <br>
        <?php endforeach; ?>
 
    <?php
}
function wpqp_fields_setting_skills(){

    $options = get_option( 'wpqp_fields_options' );
 echo '<div id="skills_list" >';
    if(isset($options['skills']))
        foreach ( $options['skills']  as $skill ): 
            ?>
            <div class="item_setting_skills">
                <input type="text" style="display:none;" id="wpqp_fields_setting_skills"
                name="wpqp_fields_options[skills][]"  value="<?php echo esc_attr( $skill ); ?>">
                - <?php echo esc_html( $skill ); ?>   <a href="#" class="skills_delete_a"> Delete </a>
            </div>
        <?php endforeach; ?>
    </div>
    <input   type="text" id="skills_add_txt" name="skills_add"> <a href="#" id="skills_add_a">Add </a>
    <script>
    jQuery(document).ready(function () {
        jQuery('a#skills_add_a').on("click",function(){
            console.log("hello");
            text = jQuery("#skills_add_txt").val();
            var out = '<div ><input type="text" name="wpqp_fields_options[skills][]" style="display:none;" value="'+text+'"> -'+text+'<a href="#" class="skills_delete_a"> Delete </a></div>'
            jQuery("#skills_add_txt").val("");
            jQuery("#skills_list").append(out);
        })
        jQuery('#skills_list').on("click",'a.skills_delete_a',function(){
            jQuery( this ).parent().remove();
        })
     })

    </script>
        
    <?php

}
function wpqp_fields_setting_active(){
    $options = get_option( 'wpqp_fields_options' );
    ?>

    <input   type="checkbox" id="wpqp_fields_setting_active"
    name="wpqp_fields_options[active]"
    value="1" 
    <?php checked( 1 == $options['active']  )  ?>
    >
        
    <?php

}
function wpqp_fields_setting_content_type() {
    $options = get_option( 'wpqp_fields_options' );
   
    $args       = array(
        'public' => true,
    );
    $post_types = get_post_types( $args, 'objects' );
    ?>
     
    <select multiple  id='wpqp_fields_setting_content_type' name='wpqp_fields_options[content_type][]'>
        <?php 
        foreach ( $post_types as $post_type_obj ):
            $labels = get_post_type_labels( $post_type_obj );
            ?>
            <option 
            <?php
                if (in_array(esc_attr( $post_type_obj->name ) , $options['content_type']  ))  echo "selected" ;
            ?>
             value="<?php echo esc_attr( $post_type_obj->name ); ?>"><?php echo esc_html( $labels->name ); ?></option>
        <?php endforeach; ?>
    </select>
    <?php 
   // echo "<input id='wpqp_fields_setting_content_type' name='wpqp_fields_setting_content_type[content_type]' type='text' value='" . esc_attr( $options['content_type'] ) . "' />";
}
