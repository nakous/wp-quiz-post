<?php

function wpqp_list_theme($post_id){
    $post   = get_post( $post_id );
    $question = json_decode(get_post_meta( $post_id, FIELD_QUESTIONS, true ));
    //print_r($_POST);
     wp_enqueue_style( 'sqp-admin-style', PLUGIN_URL. 'css/post.css',array() );
   //  SITE_URL
   wp_enqueue_script('wp_quiz_theme_list', PLUGIN_URL . 'js/theme.list.js',array( 'jquery' ), false, true ); 
    
?>
 <script>
 
    SITE_URL = '<?php echo SITE_URL ; ?>';
</script>
<section id="quiz-app-list" > 
                <header>
  
						<h1 class="title ">Quiz : <?php echo $post->post_title ; ?></h1>
				 
				</header>
                             <div class="text-center" >
								<?php 
                                  if (has_post_thumbnail( $post->ID ) ){
                                        echo get_the_post_thumbnail( $post->ID , 'wpqp_quiz_list', array( 'class' => 'respo-img text-center' ) );
                                  }  
                                     
								?>
								<!--div class="level ">Level : {{quiz.level}}</div-->
								<div class="level "> Total Question : <?php echo count($question->questions);?></div>
								<!-- div class="level ">Time of Quiz: {{quiz.time}}:00</div -->
							</div>
					 
							 
								<?php
							    	echo get_the_content($post->ID);
								?> 
<form method="post" id="form-thime-list" action="#" onsubmit="getResult();return false"  >

							 
                                <!-- blockquote class="  ">
                                    <?php
                                    $options = get_option( 'wpqp_fields_options' );
                                     if(isset($options['help'] )) echo $options['help']  ; 
                                    ?> 
                                </blockquote --> 
	<ol  id="wpqp-questions" class="questions"   >
			 
    <?php
    // PHP FOR EACH LOOP HERE TO DISPLAY OUR RESULTS
    foreach($question->questions as $row)
     {
         ?>
				 
				<li class="questionline" >
					<!-- questionTitle -->
					<div class=" ">
						<div class="  ">
							<div class="p-2" ><?php echo  $row->body ?></div> 
							<!-- quizOptions -->
						</div>
						<div class="col-left  height-300">
							<ul class="optionContainer  ">
                            <?php
    // PHP FOR EACH LOOP HERE TO DISPLAY OUR RESULTS
    foreach($row->answers as $answers)
     {
         ?>
									<li class="answer"   >
										  <input class="input-answer" name="choose[<?php echo $row->id ;?>]"  type="radio"   value="<?php echo $answers->id ;?>" id="'flexCheckDefault<?php echo $answers->id ;?>">
										  <label class="label-answer" for="'flexCheckDefault<?php echo $answers->id ;?>"  >
											 <?php echo $answers->body; ?>
	    									  </label>
                                     </li>
                                     <?php
                }
         ?>
							</ul>
						</div>
					</div>
        </li>
				 
                <?php
         }
        ?>
		 </ol>
         <input class="quiz-field-text" type="email" style="display:none;" value="anony@anony.com" name="mail" placeholder="Your Email*"  > 
         <input   type="hidden" name="post"  value="<?php echo $post_id ;?>"  > 
		<input class="quiz-field-text" type="text" style="display:none;" value="anony" name="name" placeholder="Your Name*"  >
        <input class="quiz-field-text" type="submit"   value="Submit">  
            
        </form>
        <script>
            getResult = function(){
                  data = jQuery("form").serialize() ;

                  console.log(data);
            };
        </script>   
		 <div   class="swapping-squares-spinner"   style="display:none;"  >
						<div class="square"></div>
						<div class="square"></div>
						<div class="square"></div>
						<div class="square"></div>
					  </div>
				<div id="wpqp-result" class="inner-body height-300"  style="display:none;" > 
					
					<div class="question-card"  >
						<div class="title text-center">
							  Result
						</div>
						<div class="display-score-1  text-center">
							  0%
						</div>
						<p class="display-score-2">
							  0 / <?php echo count($question->questions);?> Questions
						</p>
					</div> 
				</div> 
				 

</section>
<?php
}