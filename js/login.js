$(document).ready(function() {

	$('#password').keypress(function(e) {
	    var keycode = (e.keyCode ? e.keyCode : e.which);
	    if (keycode == '13') {
	        acceder();
	        e.preventDefault();
	        return false;
	    }
	});


	$("#enviar").on("click", function(){
        $(".error-form").hide();
        
        var email = $("#email").val();       
        var mensaje = $("#mensaje-txt").val();

        var enviar = true;

        if($("#nombre").val() == "" || $("#nombre").val() == null){
            $( "#nombre").addClass( "is-invalid" );
            enviar = false;
        }
        else{
        	$( "#nombre").removeClass( "is-invalid" );
			$( "#nombre").addClass( "is-valid" );
        }


        if($("#aPaterno").val() == "" || $("#aPaterno").val() == null){            
            $("#aPaterno").addClass( "is-invalid" );
            enviar = false;
        }
        else{
        	$( "#aPaterno").removeClass( "is-invalid" );
			$( "#aPaterno").addClass( "is-valid" );
        }


        if($("#telefono").val() == "" || $("#telefono").val() == null || isNaN($("#telefono").val())){
			$("#telefono").addClass( "is-invalid" );
        	enviar = false;
        }
        else{
        	$( "#telefono").removeClass( "is-invalid" );
			$( "#telefono").addClass( "is-valid" );
        }


        if($("#mensaje").val() == "" || $("#mensaje").val() == null){            
            $("#mensaje").addClass( "is-invalid" );
            enviar = false;
        }
        else{
        	$( "#mensaje").removeClass( "is-invalid" );
			$( "#mensaje").addClass( "is-valid" );
        }

        
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        if (caract.test($("#email").val()) == false){
            enviar = false;
            $("#email").addClass( "is-invalid" );
        }
        else{
        	$( "#email").removeClass( "is-invalid" );
			$( "#email").addClass( "is-valid" );
        }
        
        if(enviar){

            var datos = {
                'nombre' : $("#nombre").val(),
                'aPaterno' : $("#aPaterno").val(),
                'aMaterno' : $("#aMaterno").val(),
                'telefono' : $("#telefono").val(),
                'celular' : $("#celular").val(),
                'email' : $("#email").val(),                
                'mensaje' : $("#mensaje").val()
            };
            
            $.ajax({
                type: 'POST',
                url : 'process.php',
                data : datos,
                dataType : 'json',
                encode: true,
                success: function(json){
                    console.log("json: " + json);
                    $("#contacto").hide("slow");
                    $("#confirmacion").show("slow");
                },
                error: function(xhr, status){
                    $("#danger-form").show("slow");
                }                
            });
        }
        else
            return false;
    });

});

function acceder(){
	$("#error-login").hide("slow");
	var envio = true;
	if($("#username").val() == "" || $("#username").val() == null){
        envio = false;
		$("#error-login").modal();
	}
	if($("#password").val() == "" || $("#password").val() == null){
        envio = false;
		$("#error-login").modal();
	}

	if(envio)
		$("#login").submit();
}