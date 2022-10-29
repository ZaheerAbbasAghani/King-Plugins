jQuery(document).ready(function(){

	jQuery(document).on("click",".importManually", function(e){
		e.preventDefault();

		jQuery(this).text("Importing....");
		jQuery(this).attr("disabled", true);
		var data = {
			'action': 'dix_manually_import',
			'import': 1
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(dix_ajax_object.ajax_url, data, function(response) {

			//console.log(response);
			jQuery(".importManually").text("Click once to Import");
			jQuery(".importManually").attr("disabled", false);
			jQuery(".response").html(response);
		});
	});

	 jQuery('#example').DataTable( {
        "lengthMenu": [[10, 20, 30, -1], [10, 25, 50, "All"]],
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );


jQuery(document).on('click','table#example tbody tr td input[type="text"]', function (event) {  
       event.preventDefault();
       //do something
       jQuery(this).prop('disabled', true);
 });



document.querySelectorAll("table#example tbody tr td").forEach(function(node){
	node.ondblclick=function(){
		var val=this.innerHTML;
		var input=document.createElement("input");
		if (jQuery(this).find('input').length == 0) { 
			input.value=val;
		}
		input.onblur=function(){
			var val=this.value;
			var id = jQuery(this).parent().attr('data-id');
			var label = jQuery(this).parent().attr('data-label');
  			var data = {
				'action': 'dix_update_product_name',
				 dataType: 'json',
				'id':id,
				'val':val,
				'label':label,
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(dix_ajax_object.ajax_url, data, function(response) {
					//alert(response);
					//location.reload();
				
			});

			this.parentNode.innerHTML=val;

		}
		this.innerHTML="";
		this.appendChild(input);
		input.focus();
	}
});


jQuery(document).on("change",".dixCategory", function(){
//jQuery(".dixCategory").on("change", function(){

	if(confirm("Are you sure?")){

		var val = jQuery(this).find("option:selected").val();
		var id = jQuery(this).attr("data-id");

		var data = {
			'action': 'dix_update_category',
			'val': val,
			'id':id,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(dix_ajax_object.ajax_url, data, function(response) {
			alert(response);
		});
	}else{
		return false;
	}
});


jQuery(document).on("change",".dixFlags", function(){
//jQuery(".dixFlags").on("change", function(){

	if(confirm("Are you sure?")){

		var val = jQuery(this).find("option:selected").val();
		var id = jQuery(this).attr("data-id");

		var data = {
			'action': 'dix_update_flags',
			'val': val,
			'id':id,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(dix_ajax_object.ajax_url, data, function(response) {
			alert(response);
		});
	}else{
		return false;
	}
});





});

