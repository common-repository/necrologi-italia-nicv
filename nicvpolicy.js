(function($){
	$(document).ready(function(){

		$("#button_privacy").click(function(e){


			$.ajax({
				type: "POST",
			  url: my_vars.ajaxurl, 

        data: {
          action: 'nicv_policy',
        },

				dataType: "html",
				success: function(response){

          //alert(response);
          //$("#privacy_content").html(response);
          w = window.open("", "privacy", "width:500, height:150");
          w.document.write(response);
				},
				error: function(){
					//$("#nicvmail-response").html('<br/><br/><p class="nicv-mail-text" style="color:red;font-size:20px;" >Errore</p>');
				}
			});
	  	});
	});
})(jQuery);