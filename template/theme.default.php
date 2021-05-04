<?php

function wpqp_default_theme($post_id){
    $post   = get_post( $post_id );
    //print_r($post );
     //Style
     //wp_enqueue_style( 'sqp-bootstrap', BOOTSTRAP_CSS, array(  ), false, true );
     //wp_enqueue_style( 'sqp-admin-style', plugin_dir_url(WPPQ_FILE) . '../css/post.css',array('sqp-bootstrap') );
     wp_enqueue_style( 'sqp-admin-style', PLUGIN_URL. 'css/post.css',array() );
     
     //JS
    // wp_enqueue_script( 'sqp-bootstrap-js', BOOTSTRAP_JS , array( 'jquery' ), false, true);
     wp_enqueue_script( 'vuejs', VUEJS , array( 'jquery' ) , false, true );
     wp_enqueue_script( 'axios', AXIOS , array( 'vuejs' ) , false, true );
	 wp_enqueue_script( 'gstatic-chart', 'https://www.gstatic.com/charts/loader.js' , array() , false, true );
     wp_enqueue_script('wp_quiz_post_script', PLUGIN_URL . 'js/post-quiz.js',array( 'vuejs','axios' ), false, true ); 
	$qs =  get_post_meta( $post_id, FIELD_QUESTIONS, true );
	if(empty($qs))
		return;
?>

<script>
    QUESTIONS =<?php echo $qs; ?>;
    ID = <?php echo $post_id; ?>;
    SITE_URL = '<?php echo SITE_URL ; ?>';
</script>
<section class="" id="quiz-app">
	<!--questionBox-->
	<div class="questionBox"   >
			<div class="firstpage" v-if="status== 'created'">
				<header>
					<div class=" ">
						<h1 class="title ">Quiz : <?php echo $post->post_title ; ?></h1>
						
					</div>	 
				</header>
				<div class="inner-body">
					<div class="row-col">
						<div class="col-left">
							<div class="text-center" >
								<?php 
                                  if (has_post_thumbnail( $post->ID ) ){
                                        echo get_the_post_thumbnail( $post->ID , 'wpqp_quiz', array( 'class' => 'respo-img' ) );
                                  }  
                                     
								?>
								<!--div class="level ">Level : {{quiz.level}}</div-->
								<div class="level "> Total Question : {{qCount}}</div>
								<!-- div class="level ">Time of Quiz: {{quiz.time}}:00</div -->
							</div>
						</div>
						<div class="col-right ">
							<div class="">
								<?php
								echo get_the_content($post->ID);
								?> 
								 <div class="user-info" style="display:none;">
									<input class="quiz-field-text" type="email" placeholder="Your Email*" v-model="user.mail"> 
									<input class="quiz-field-text" type="text" placeholder="Your Name*" v-model="user.name">
									<p><span style="color:red;">*</span> To start this quiz, please enter your name and email </p>
								</div>
								<blockquote class="  ">
                                    <?php
                                    $options = get_option( 'wpqp_fields_options' );
                                     if(isset($options['help'] )) echo $options['help']  ; 
                                    ?> 
                                </blockquote> 
							</div>
						</div>
					</div>
				</div>
				<!--quizFooter: navigation and progress-->
				<footer class="questionFooter">			 
						<!--  :disabled="(user.mail !='' && user.name !='' && validEmail(user.mail) === true) ? false : true" -->
						<button class="btn-quiz"  v-on:click="startQuiz()">Start Quiz</button>
				</footer>
			</div>
			<!--qusetionContainer-->
			<div class="questionContainer" v-if="status== 'started'" >
				<header>
					<div class="">
						<h1 class="title"><?php echo $post->post_title ; ?></h1>
						 
					</div>
					
					<!--progress-->
					<div class="progressContainer">
						<progress class="progress progress-bar progress-bar-striped   is-small" :value="(qIndex/questinnaire.questions.length)*100" max="100">{{(qIndex/questinnaire.questions.length)*100}}%</progress>
						
					</div>
					<!--/progress-->
				</header>
				<div class="inner-body" v-for="(question, indq) in questinnaire.questions" :key="indq" v-if="id==question.id">
					<!-- questionTitle -->
					<div class="row-col">
						<div class="col-right  height-300 ">
							<div class="p-2" v-html="question.body"></div> 
							<!-- quizOptions -->
						</div>
						<div class="col-left  height-300">
							<div class="optionContainer  ">
									<div class="form-check-answer"  v-for="(response, index) in question.answers" :key="index">
										  <input class="form-check-input-answer" :name="'choose' +question.id"  type="radio" v-model="answers[question.id]"  :value="response.id" :id="'flexCheckDefault' + response.id  ">
										  <label class="form-check-label-answer" :for="'flexCheckDefault' + response.id  "  v-html="response.body">
											 
										  </label>
									</div>
							</div>
						</div>
					</div>
				</div>
				<!--quizFooter: navigation and progress-->
				<footer class="questionFooter  ">
						<!-- back button -->
						<button  class="btn-quiz " v-on:click="prev();" v-if="qIndex > 0" >Back</button>
						<!-- next button -->
						<button  class="btn-quiz float-right"  v-on:click="next();" v-if="qIndex <= questinnaire.questions.length-2" >
						 Next
						</button>
						<button  class="btn-quiz float-right"  v-on:click="finish();" v-if="qIndex == questinnaire.questions.length -1" >
						 Finish
						</button>
					
					<!--/pagination-->
				</footer>
				<!--/quizFooter-->
			</div>
			<!--quizCompletedResult-->
			<div v-if="status == 'finished'"   class="quizCompleted has-text-centered">
			
				<header>
					<div class=" ">
						<h1 class="title   "><?php echo $post->post_title ; ?></h1>
						 
					</div>
					 
				</header>
				<div class="inner-body height-300"   v-bind:class="{ 'spinner-loading': loading  }"> 
					 <div v-if="loading == true" class="swapping-squares-spinner"    >
						<div class="square"></div>
						<div class="square"></div>
						<div class="square"></div>
						<div class="square"></div>
					  </div>
					<div v-if="loading == false">
						<div id="AnswersChart" style="width: 50%; height: 250px; display:none;"></div>
						<div class="display-score text-center">
							  Your Score 
						</div>
						<div class="display-score-1  text-center">
							  {{ result.percentage  }}%
						</div>
						<p class="display-score-2">
							{{ result.score }}/{{ qCount  }}  
						</p>
						<p class="" style="margin-bottom: 13px;">
							Questions 
						</p>
					</div> 
				</div> 
				<footer class="questionFooter text-center  "> 
					 
						<a  class="btn-quiz  " href="<?php echo SITE_URL ; ?>"   >GO HOME</a>
				</footer>
			</div> 
	</div>
	<!--/questionBox
	-->
</section>
<?php
}