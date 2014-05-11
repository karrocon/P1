/*
* $: affects whole document
* Description: defines initial behaviours when document is loaded
*/
!function ($) {
	$(function(){
		// carousel demo
		//$('#myCarousel').carousel()
		
		if($("#hidden_verify_msg").length){
			$("#div_modal_verify_body").text(($("#hidden_verify_msg").attr("tag")));
			$("#div_modal_verify").modal("show");
		}
		
		$.get("php/access.php", "do=lang", function(data) {
			data = eval(data);
			
			$(".ml").each(function() {
				$(this).text(data["es"][$(this).attr("ml-label")]);
			});
			
			$(".ml-placeholder").each(function() {
				$(this).attr("placeholder", data["es"][$(this).attr("ml-label")]);
			});
		});
	})
}(window.jQuery)

/*
* $: affects form with id="form_login"
* Description: defines submit event behaviour
*/
$("#form_login").submit(function (event) {
	event.preventDefault();
	
	$.post("php/access.php", "do=login&" + $(this).serialize(), function(data) {
		//alert(data);
		if (data == "1"){
			//$(".dropdown-toggle").dropdown("toogle");
			window.location.replace("./");
			//location.reload(true);
			//header("Location: http://www.google.es");
			//location.href = "http://www.marca.com";
			//window.location = "http://www.marca.com";
		}else{
			$("#div_alert_error_login").removeClass("hide");
			$("#div_alert_error_login").effect("shake", { times:2, distance:10 }, 500);
		}
	});
});

/*
* $: affects element with id="btn_register"
* Description: register button functionality
*/
$("#btn_register").click(function () {
	$(".dropdown-toggle").dropdown("toogle");
	// TODO. Abrir register.php para hacer sign up
});

/*
* $: affects form with id="form_login"
* Description: defines submit event behaviour
*/
$("#form_register").submit(function (event) {
	event.preventDefault();
	
	$.post("php/access.php", "do=register&" + $(this).serialize(), function(data) {
		alert(data);
		if (data == "success"){
			//$(".dropdown-toggle").dropdown("toogle");
			// TODO. Cambiar lo que se muestra para mensaje de un correo ha sido enviado...
		}else{
			$("#div_alert_error_register").removeClass("hide");
			$("#div_alert_error_register").effect("shake", { times:2, distance:10 }, 500);
		}
	});
});


/*
* $: affects inputs and label with class dropdown-menu.
* Description: stop propagation in order to avoid dropdown div to toogle when clicked.
*/
$('.dropdown-menu input, .dropdown-menu label, .dropdown-menu button').click(function(e) {
	e.stopPropagation();
});