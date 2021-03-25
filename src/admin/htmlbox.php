<?php
/**
 * ADD Box Questions
 */
function quiz_html( $post ) {
    $value1 = get_post_meta( $post->ID, FIELD_QUESTIONS, true );
    $value2 = get_post_meta( $post->ID, FIELD_ANSWERS, true );
    wp_enqueue_editor();
    ?>
    <script>
        FIELD_QUESTIONS = <?php  if(empty($value1))  echo "{}" ; else echo $value1 ;?> ;
        FIELD_ANSWERS = <?php  if(empty($value2))   echo "{}" ;else echo $value2 ;?> ;
        QTEXT =  '<?php  echo FIELD_QUESTIONS ?>';
        ATEXT = '<?php  echo FIELD_ANSWERS ?>';
        OPTION = <?php  echo json_encode (get_option( 'wpqp_fields_options' )); ?>;
        
    OPTION
    </script>
    <div id="app-quiz">
 
        <button  class=""  v-on:click="add_q()">Add Question</button>
        <button  class=""  v-on:click="update_question()">Update Question</button>
        <button  class=""  v-on:click="update_answers()">Update answers</button>
        <div id="form-edit-add" class="card card-handle" v-if="form_acive == true">
                     <div class="card-title">
                        
                         <textarea id="editor-question-body" v-model="new_question.body" placeholder="Question" ></textarea>
       
                        <input type="text" v-model="new_question.ref" placeholder="Footer text" >
                        <select v-model="new_question.skill" >
                          <option disabled value="">Please select Skills</option>
                            <option v-for="skill in options.skills" v-bind:value="skill">
                                {{ skill }}
                            </option>
                        </select>
                        
                        <select   v-model="new_question.type" >
                          <option disabled value="">Quiz Type</option>
                            <option v-for="type in options.quiz_types" v-bind:value="type">
                                {{ type }}
                            </option>
                        </select> 
                        <input type="text" v-model="new_question.level"  placeholder="Level">
                        <select   v-model="new_question.answer_type" >
                            <option disabled value="">Answer type</option>
                            <option v-for="atype in options.answer_type" v-bind:value="atype">
                                {{ atype }}
                            </option>
                        </select> 
                    </div> 
                    <ul class="cart-list-group">
                                <li class="list-group-item"  v-for="(response, index) in new_question.answers" :key="index">
                                        <input class="form-check-input" type="radio"    :value="response.id" :id="'flexCheckDefault' + response.id ">
                                        <label class="form-check-label" v-if="status_form == 'add' || status_form == 'update'"  :for="'flexCheckDefault' + response.id " v-html="response.body"  >
                                            
                                        </label>
                                        <textarea  rows="3" width="100%"  v-if="status_form == 'update0'"  placeholder="Answer"  >{{response.body}}</textarea>
                                        <button  class=""  v-on:click="delete_a(response)">Delete</button>
                                </li>
                                <li class="list-group-item"  >
                                        Ref : {{answer.id}} - 
                                        <textarea id="edit-answer-textarea" rows="3"  placeholder="Answer"  v-model="answer.body"></textarea>
                                        <button  class=""  v-on:click="add_answer(answer)">add</button>
                                </li>
                                
                    </ul>  
                    <div class="card-footer">
                        <button  class=""  v-on:click="cancel_q(new_question)">Cancel</button>
                        <button  class="" v-if="status_form == 'add'"  v-on:click="save_q(new_question)">Save</button>
                        <button  class="" v-if="status_form == 'update'"  v-on:click="save_update_q(new_question)">Update</button>
                    </div>
                </div>
        <div class="questions" v-for="(question, indq) in questions.questions" :key="indq"  >
                 
                <div class="card card-handle" :id="'card-question' +question.id ">
                     <div class="card-title">
                         <div class="" v-html="question.id + ' - '+ question.body"></div>
                    </div> 
                    <ul class="cart-list-group">
                                <li class="list-group-item"  v-for="(response, index) in question.answers" :key="index">
                                        <input class="form-check-input" :name="'choose' +question.id"  type="radio" v-model="answers[question.id]"  :value="response.id" :id="'flexCheckDefault' + response.id  ">
                                        <label class="form-check-label" :for="'flexCheckDefault' + response.id  "  v-html="response.body">
                                            
                                        </label>
                                </li>
                                
                    </ul> 
                    <div class="card-footer"> 
                        <button  class=""  v-on:click="delete_q(question)">Delete</button>
                        <button  class=""  v-on:click="update(question)">Update</button>
                    </div>
                </div>
        </div>
    </div>
     
        
    <?php
}