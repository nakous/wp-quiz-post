getResult = function(){
    try {
        data = jQuery("form#form-thime-list")  ;
        jQuery(".swapping-squares-spinner").show();
        jQuery("#form-thime-list").hide();

        jQuery.post( SITE_URL+ '/wp-json/quiz/apiv1/formanswers',getFormData(data), function( data ) {
          //console.log(data);
          if(data.errer ===undefined){
            jQuery("#wpqp-result").show();
            jQuery("#wpqp-result .display-score-1").html(data.percentage + "%");
            jQuery("#wpqp-result .display-score-2").html(data.score + "/"+ data.total + " Questions");
            jQuery(".swapping-squares-spinner").hide();
          }else{
            alert(data.message);
            jQuery(".swapping-squares-spinner").hide();
            jQuery("#form-thime-list").show();
          }
          

        });
        console.log(getFormData(data));
      }
      catch(err) {
        console.log(err);
      }
    
};

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    jQuery.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}
