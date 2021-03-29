//Vue.config.devtools = true;
var quiz = {
    title: "Java",
    skill:'java',
    level : "Level 1",
    time:1, // by munite
    description: "",
  };
var app = new Vue({
    el: "#quiz-app",
    data: {
      quiz: quiz,
      questinnaire: QUESTIONS,
      status:"created",
      answers:{}, 
	  loading:false,
	  qCount : 0,
	  qIndex:0,
		id:"",
		user:{
			mail:"",
			name:""
		},
		result:{
			score:0,
			percentage:0
		}
    },
    filters: {
      charIndex: function (i) {
        return String.fromCharCode(97 + i);
      } 
    },
    created: function(){
        this.load();
    },
    methods: {
		load:function(){
             this.qCount = this.questinnaire.questions.length ;
			 if(localStorage.getItem("userQuiz") !== null)
				this.user = JSON.parse(localStorage.getItem('userQuiz'));
        },
 
        startQuiz:function(){
            this.status = 'started'; 
			this.id = this.questinnaire.questions[this.qIndex].id;
			localStorage.setItem('userQuiz',JSON.stringify(this.user));
			//this.finish();
        },

      next: function () { 
		this.qIndex++;
		this.id = this.questinnaire.questions[this.qIndex].id;
		console.log(this.answers);
		console.log(JSON.stringify(JSON.parse(JSON.stringify(this.answers))));
      },
	  prev: function () {
		this.qIndex--;
		this.id = this.questinnaire.questions[this.qIndex].id;
      },
      finish: function(){
		  this.loading = true;
			this.status = 'finished';  
		    that = this;
				axios.post(SITE_URL+"/wp-json/quiz/apiv1/addanswers",{ 
					answers:JSON.stringify(this.answers), 
					time:new Date(),
					post:ID,
					status:this.status,
					user:this.user
			   }).then(function (response) {
					console.log(response.data);
					that.result =response.data;
					// that.result.score = response.data.score;
					// that.result.pourcentage = response.data.pourcentage;
					 that.loading = false;
				})
        
      },

	   validEmail: function (email) {
		   var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		  console.log(re.test(email));
		  return re.test(email);
		},
      // Return "true" count in userResponses
      score: function () {
        var score = 4;
        var skip = 0;

        
        return score;
      } 
    } 
});