<?php


function wpqp_Answers_list()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'quiz_answers'; 
    $total = $wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." AS a");
    $post_per_page = 10;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $offset = ( $page * $post_per_page ) - $post_per_page;
    
    // QUERY HERE TO GET OUR RESULTS 
    $results = $wpdb->get_results("SELECT * FROM ".$table_name." LIMIT $post_per_page OFFSET $offset");
    ?>
         <table class="table_answer">
             <tr>
                 <td>Name</td>
                 <td>Email</td>
                 <td>Score</td>
                 <td>percentage</td>
                 <td>Quiz</td>
             </tr>
         
         <?php
    // PHP FOR EACH LOOP HERE TO DISPLAY OUR RESULTS
    foreach($results as $row)
     {
         ?>
          <tr>
                 <td><?php echo $row->name ;?></td>
                 <td><?php echo $row->mail ;?></td>
                 <td><?php echo $row->score ;?></td>
                 <td><?php echo $row->percentage ;?></td>
                 <td><?php
                 $post   = get_post( $row->postid );
                 echo $post->post_title ;?></td>
             </tr>
         <?php 
     }
    // END OUR FOR EACH LOOP
    
    ?>
    </table>
    <?php 
    echo '<div class="pagination">';
    echo paginate_links( array(
    'base' => add_query_arg( 'cpage', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total / $post_per_page),
    'current' => $page,
    'type' => 'list'
    ));
    echo '</div>';
 
}  