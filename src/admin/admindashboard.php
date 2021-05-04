<?php

/**
 * Page WPQP Admin Dashboard
 */
function WPQPAdminDashboard()
{
    global $wpdb;
    $sql_total = $wpdb->get_var("SELECT count(*) FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = 'wpqp_post_question'");
    $sql_publish = $wpdb->get_var("SELECT count(*) FROM `".$wpdb->prefix."postmeta` pm,wp_posts p WHERE pm.`meta_key` = 'wpqp_post_question' and pm.`post_id` = p.id and p.post_status = 'publish' ;");
    $sql_draft = $sql_total - $sql_publish;

    $table_name = $wpdb->prefix . 'quiz_answers'; 
     $sqla_total =$wpdb->get_var("SELECT COUNT(*) FROM ".$table_name);
     $sqla_today =$wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." where DATE(created) = CURDATE() ");

     $result = $wpdb->get_results('SELECT DATE_FORMAT( (DATE(NOW()) - INTERVAL `day` DAY), "%d-%M") AS `DayDate`, COUNT(`id`) AS `co` 
     FROM ( SELECT 0 AS `day` UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 ) AS `week` 
     LEFT JOIN wp_quiz_answers on DATE(`created`) = (DATE(NOW()) - INTERVAL `day` DAY) 
     GROUP BY `DayDate` 
     ORDER BY `DayDate` ASC ');
   /*$arr =array();
    for($i=0; $i> 7 ; $i++){
        $arr[]
    }
    foreach($result as $row){
        echo " <td>".$row->c."<br>";
        echo $row->d. '/' .$row->m. '/'.$row->y."</td>";
    }*/
?>
<style>
.col-dash-container{
    width:33%;
    float:left;
    
}
.padd-right{
    margin-right: 10px;
}
</style>
 <div class="wrap">
    <h2>Dashnoard Quiz post</h2>
    <div class="col-dash-container">
        <div class="card padd-right">
             
                <h2>Quiz</h2>
                <p class="about-description">statistic about the number of quiz exist in your website </p>
            
            quiz : <?php echo $sql_total ;?> <br>
            draft : <?php echo $sql_draft ;?> <br>
            online : <?php echo $sql_publish ;?>
        </div>
    </div>
    <div class="col-dash-container">
        <div class="card padd-right">
            <div class="welcome-panel-content">
                <h2>Answers</h2>
                <p class="about-description">statistic about quiz taked by user </p>
            </div>
            Today: <?php echo $sqla_today ;?> answers <br>
            Total: <?php echo $sqla_total ;?> answers  <br>
            <div id="AnswersChart" style="width: 100%; height: 250px"></div>
            
        </div>
    </div>
    <div class="col-dash-container">
        <div class="card">
            <div class="welcome-panel-content">
                <h2>About Us </h2>
                <p class="about-description">statistic about quiz taked by user </p>
            </div>
            Plugun in WP <br>
            Github
        </div>
    </div>
 </div>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Day');
      data.addColumn('number', 'Test');
      data.addRows([
          <?php
            foreach($result as $row){
                echo "['".$row->DayDate."'," .$row->co."],";
            }
            ?>
        
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.LineChart(document.getElementById('AnswersChart'));
      chart.draw(data, null);
    }
  </script>


<?php
}