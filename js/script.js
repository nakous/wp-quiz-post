console.log("Quiz post v1.1.1");

Vue.config.devtools = true;
 
var app = new Vue({
    el: "#app-quiz",
    data: { 
      questions: FIELD_QUESTIONS,
      answers: FIELD_ANSWERS
    },
    filters: { 
    },
    created: function(){
     
    },
    methods: {
		 
    } 
});