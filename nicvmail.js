(function($){
	$(document).ready(function(){

		$("#bottone").click(function(e){

      document.getElementById("bottone").disabled = true;
      e.preventDefault();

			var privacy = 0;
      if(document.getElementById('privacy').checked) {
        privacy = 1;
      }

      var pubblicato = 0;
      if(document.getElementById('message-consent').checked) {
        pubblicato = 1;
      }

		//	var consenso = $("input[type='radio']:checked").val();

			$("#nicvmail-content").hide("slow");



      $.toast({
        /*heading: 'Messaggio inviato',*/
        text: 'Invio in corso',
        icon: 'info',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'mid-center',
        stack: false,
      });


var host = "/wp-content/plugins/"; 
var url = host+"necrologi-italia-nicv/nicvmail.service.php";



			$.ajax({
				type: "POST",
			  url: my_vars.ajaxurl, 

        data: {
          action: 'condoglianze',
				  id_defunto: $("#id_defunto").val(),
            mittente: $("#mittente").val(),
             replyto: $("#replyto").val(),
                 msg: $("#msg").val(),
                 pubblicato: pubblicato,
           nicaptcha: $("#nicaptcha").val(),
             privacy: privacy,

          ringraziamento_indirizzo: $("#indirizzo-fisico").val(),
              ringraziamento_email: $("#email_ringraziamento").val(),
           ringraziamento_messaggi: $("#messaggi").val(),
             ringraziamento_numero: $("#numero-telefono").val(),
        },

				dataType: "html",
				success: function(response){

					if(response.trim().substr(0,3) == '+ok'){


            $.toast({
              heading: 'Messaggio inviato',
              text: 'Grazie, provvederemo quanto prima a recapitare il messaggio alla famiglia',
              icon: 'success',
              loader: false,        // Change it to false to disable loader
              loaderBg: '#9EC600',  // To change the background
              position: 'mid-center',
              stack: false,
              hideAfter: 5000, 
            });

            var frm = document.getElementById("nicvmail-form");
            frm.reset();

setTimeout(function() {
  window.location.reload()
}, 6000);

					}else{
                        

			
						$("#nicvmail-content").show("slow");

            $("#nicvmail-response").html('');
            $.toast({
              heading: 'Errore',
              text: response.slice(4,-1),
              icon: 'error',
              loader: false,        // Change it to false to disable loader
              loaderBg: '#9EC600',  // To change the background
              position: 'mid-center',
              stack: false,
            });

            document.getElementById("bottone").disabled = false;

					}
				},
				error: function(){
					//$("#nicvmail-response").html('<br/><br/><p class="nicv-mail-text" style="color:red;font-size:20px;" >Errore</p>');
				}
			});
	  	});
	});
})(jQuery);
