<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class wpqp_Answers_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $data = $this->table_data( $perPage, $currentPage  );
        usort( $data, array( &$this, 'sort_data' ) );

       
        global $wpdb;
    $table_name = $wpdb->prefix . 'quiz_answers'; 
    $totalItems = $wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." AS a");
        

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        //$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'            => 'ID',
            'name'         => 'Name',
            'mail'          => 'Email',
            'post'       => 'Quiz Title',
            'score'         => 'Score',
            'percentage'      => 'Percentage',
            'status'      => 'Status',
            'ip'      => 'Ip',
            'created'       => 'Created'
        );
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('created' => array('created', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data( $per_page = 5, $page_number = 1 ) {

        global $wpdb;
      
        $sql = "SELECT * FROM {$wpdb->prefix}quiz_answers";
      
        if ( ! empty( $_REQUEST['orderby'] ) ) {
          $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
          $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }else{
            $sql .= ' ORDER BY created DESC';
        }
      
        $sql .= " LIMIT $per_page";
      
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
   
        $result = $wpdb->get_results( $sql );
        $data=[];
      foreach($result as $row){
        $line=[];
        $post   = get_post( $row->postid );
        if(isset($post->post_title))
            $line["post"] = $post->post_title ;
        else
            $line["post"] = 'Not Found';

            $line["id"] = $row->id ;
            $line["name"] = $row->name ;
            $line["mail"] = $row->mail ;
            $line["score"] = $row->score ;
            $line["created"] = $row->created ;
            $line["percentage"] = $row->percentage ;
            $line["ip"] = $row->ip ;
            $line["status"] =(!empty($row->status))  ?  $row->status : "Init" ;
            $data[] = $line;
      }
        return $data;
    }
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        
        switch( $column_name ) {
            case 'id':
            case 'name':
            case 'mail':
            case 'score':
            case 'created':
            case 'post':
            case 'percentage':
            case 'ip':
            case 'status':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'created';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}
function wpqp_Answers_list()
{
 /*   global $wpdb;
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
                 if(isset($post->post_title))
                     echo $post->post_title ;
                 else
                     echo 'Not Found';
                 ?></td>
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
 */
$answersList = new wpqp_Answers_List_Table();
$answersList->prepare_items();
?>
    <div class="wrap">
        <div id="icon-users" class="icon32"></div>
        <h2>List Answers</h2>
        <?php $answersList->display(); ?>
    </div>
<?php
}