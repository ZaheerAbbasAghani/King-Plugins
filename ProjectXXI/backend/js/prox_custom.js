jQuery(document).ready(function(){
	var unique_number = Math.floor(1000 + Math.random() * 9000);

	var prox_numers = jQuery(".prox_numers").val();
	if(prox_numers==""){
		jQuery(".prox_numers").val(unique_number);
	}



});