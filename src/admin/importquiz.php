<?php
function wpqp_import_quiz(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'quiz_import'; 
   // $handle = fopen(PLUGIN_DIR."files/import-example.csv", "r");
    $row = 1;
    $csvFile = file(PLUGIN_DIR."files/import-example.csv");
    $data = [];
    foreach ($csvFile as $line) {
         
        $data[] = str_getcsv($line,";");
    }
    //print_r($data);
    $questions = [];
    $reponse = [];
    for($i=1; $i < count($data) ; $i++){
        $answers = [];
        
            $answers[]= array('body' => $data[$i][1] ,'id' =>  'Q'.$i."A1"  );
            $answers[]= array('body' => $data[$i][2] ,'id' =>  'Q'.$i."A2"  );
            $answers[]= array('body' => $data[$i][3] ,'id' =>  'Q'.$i."A3"  );
            $answers[]= array('body' => $data[$i][4] ,'id' =>  'Q'.$i."A4"  );
            $answers[]= array('body' => $data[$i][5] ,'id' =>  'Q'.$i."A5"  );
        $pos =$data[$i][6] - 1;
        //echo $pos;
        if(isset($answers[$pos]))
            $reponse[ 'Q'.$i ]= $answers[$data[$i][6] -1]["id"] ;

        $questions[] = Array(
             "answers" => $answers,
            "body" => $data[$i][0],
            "ref"=> "",
            "hint"=> "",
            "level"=> $data[$i][10],
            "skill"=> $data[$i][9],
            "type"=> $data[$i][8],
            "answer_type"=> $data[$i][7],
            "id" => 'Q'.$i
        );
    }
    print_r(json_encode($questions));
    print_r(json_encode($reponse));
    
    
?>
<div class="wrap">
    <h1>Import Quiz</h1>
    <div class="welcome-panel">
<table class="table_answer">

         
         <?php
     
    for($i=0; $i < count($data) ; $i++){
        echo '<tr>';
        for($k=0; $k < count($data[$i]) ; $k++){
         ?>
          
                 <td><?php echo $data[$i][$k] ;?></td>
                
             
         <?php  
        }
        echo ' </tr>';
         }

    
    ?>
    </table>
    </div>
    </div>
<?php
}