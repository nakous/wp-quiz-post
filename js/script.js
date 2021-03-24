console.log("WP Quiz post v1.1.1");

Vue.config.devtools = true;




var app = new Vue({
    el: "#app-quiz",
    data: { 
      questions: FIELD_QUESTIONS,
      answers: FIELD_ANSWERS,
      question_textarea : QTEXT,
      answers_textarea:ATEXT,
      options:OPTION,
      new_question:{},
      form_acive:false,
      Ids:[],
      status_form:"",
      answer:{id:"",body:""},
      answer_type:["radio"]
    },
    filters: { 
    },
    created: function(){
      this.indexIds();
    },
    methods: {
       // Question
       indexIds:function(){
         if(this.questions.questions !== undefined){
            this.Ids = this.questions.questions.map(obj => {
              return   obj.id.slice(1);
          })
         }
        
       },
      nextId: function(){ 
         max = 1;
        if(this.Ids.length> 0){
          max = Math.max(...this.Ids )
          if(max != null)
              max++;
           else
            max = 1;
        }else
          max = 1;
        
          return "Q"+max;
      },
      scrollTo:function(ele){
        setTimeout(function() {
          let item = document.getElementById(ele);
          if(item)
            item.scrollIntoView({behavior: "smooth" });
        }, 100);
      },
      tinymceStart:function(){
        setTimeout(function() {
            tinymce.init({
              selector: '#editor-question-body', 
              menubar: false,
              toolbar: 'undo redo | bold italic underline | link hr | alignleft aligncenter alignright | bullist numlist outdent indent blockquote | code'
            });
        }, 100);
      },
      tinymceStart_a:function(ele){
        setTimeout(function() {
            tinymce.init({
              selector:   ele, 
              menubar: false,
              toolbar: 'undo redo | bold italic underline | code'
            });
            console.log("tinymceStart_a");
        }, 200);
      },
      nextIdAnswer: function(question){ 
        max = 1;
        if(question.answers.length > 0){
          let Ids = question.answers.map(obj => {
            return   obj.id.replace(question.id+'A', '')
          }) 
            max = Math.max(...Ids );
          if(max != null)
            max++;
          else
            max = 1;
        }
        
        return question.id + "A" + max;
      },
      delete_a: function(response){
        let index =this.new_question.answers.findIndex(q => q.id === response.id);
        this.new_question.answers.splice(index, 1);
        this.update_question();this.update_answers();
      },
      delete_q: function(question){
        let index =this.getQuestionIndex(question);
        this.questions.questions.splice(index, 1);
        console.log(this.answers[question.id]);
        if(this.answers[question.id]){
          delete this.answers[question.id];
        }
        //let indexa =this.answers.findIndex(q => q === question.id);
        //this.answers.splice(indexa, 1);
        this.update_question();this.update_answers();
      },
      save_q: function(question){
        this.form_acive = false;
        this.new_question.body=tinymce.get("editor-question-body").getContent();
        if(this.questions.questions === undefined)
            this.questions.questions  = []; 
        this.questions.questions.push(this.new_question);
        this.new_question={}
        this.scrollTo('card-question' +question.id);
        tinymce.remove('#editor-question-body');
        this.indexIds();
        this.update_question();this.update_answers();
      },
      save_update_q: function(question){
        this.form_acive = false;
        this.new_question.body=tinymce.get("editor-question-body").getContent()
        //this.questions.questions.push(this.new_question);
        let index =this.getQuestionIndex(question);
        this.questions.questions[index] = this.new_question;
        this.new_question={}
        this.scrollTo('card-question' +question.id);
        tinymce.remove('#editor-question-body');
        this.update_question();this.update_answers();
      },
      getQuestionIndex:function(question){
        return   this.questions.questions.findIndex(q => q.id === question.id);
      },
      cancel_q: function(question){
        this.new_question = {}; 
        this.form_acive = false;
        tinymce.remove('#editor-question-body');
        this.update_question();this.update_answers();
      },
      update: function(question){
        this.new_question =question;
        this.answer = {id:this.nextIdAnswer(this.new_question),body:""} ;
        this.status_form = "update";
        this.form_acive = true;
        this.scrollTo('form-edit-add');
        this.tinymceStart();
        this.tinymceStart_a('#edit-answer-textarea');
        this.update_question();this.update_answers();
      },
      add_q: function(){
        this.new_question = {
          answers:[],
          body:"",
          ref:"",
          hint:null,
          level:0,
          skill:"",
          type:"",
          answer_type:"",
          id:this.nextId()
        };
        this.answer = {id:this.nextIdAnswer(this.new_question),body:""} ;
        this.status_form = "add";
        this.form_acive = true;
       this.scrollTo('form-edit-add');
       this.tinymceStart(); 
       this.tinymceStart_a('#edit-answer-textarea');
       this.update_question();this.update_answers();
      },
      update_question: function(){
        jQuery("#"+this.question_textarea).html(JSON.stringify(this.questions));
      },
      // Answer
      update_answers: function(){
        console.log(JSON.stringify(this.answers));
        jQuery("#"+this.answers_textarea).html(JSON.stringify(this.answers));
      },
      add_answer:function(answer ){
        answer.body=tinymce.get("edit-answer-textarea").getContent();
        tinymce.get("edit-answer-textarea").setContent('');
        this.new_question.answers.push(answer) ;
        this.answer = {id:this.nextIdAnswer(this.new_question),body:""} ;
        this.update_question();this.update_answers();
      },
      
    } 
});