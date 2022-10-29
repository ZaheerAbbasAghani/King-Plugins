jQuery(document).ready(function(){
	//Accordion

	jQuery("div.boss").each(function(index,value){
		var len = jQuery(this).find(".dblock").length;
		//console.log(len);
		jQuery(this).find(".jfcount").text(len);
		jQuery("#jf_accordion_"+index).accordion({
		    collapsible: true,
		    active: false 
		});

	});


	//Delete All

	jQuery("#delete_all").click(function(e){
		e.preventDefault();

		var confirm1 = confirm('Are you sure?');
	  	if(confirm1) {

			var data = {
				'action': 'jf_delete_all_records',
				'delete': 1
			};
			jQuery.post(ajax_object.ajax_url, data, function(response) {
				alert(response);
			});

		}

	});


})