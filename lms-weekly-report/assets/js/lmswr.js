jQuery(document).ready(function(){

	jQuery(".learndash_post_sfwd-courses .ld-item-list-item .ld-item-list-item-preview a").click(function(){
		var now = new Date();
		var clock_in_time = moment(now).format('YYYY-MM-DD HH:mm:ss');
		var course = jQuery(this).parents().find(".ld-item-list-items").eq(0).attr("id");
		var title = course.split('-').pop();
		var arr = [clock_in_time, title];
		if (localStorage.getItem(title) === null) {
			localStorage.setItem(title, JSON.stringify(arr));
		}

	});

	jQuery(".learndash_post_sfwd-courses .ld-expandable .ld-table-list-item a").click(function(){
		var now = new Date();
		var clock_in_time = moment(now).format('YYYY-MM-DD HH:mm:ss');
		var course = jQuery(this).parents().find(".ld-item-list-items").eq(0).attr("id");
		var title = course.split('-').pop();
		var arr = [clock_in_time, title];
		if (localStorage.getItem(title) === null) {
			localStorage.setItem(title, JSON.stringify(arr));
		}
	});


jQuery(document).bind("DOMNodeRemoved", function(e)
{
    if(jQuery(e.target).hasClass("dialog-box")){
    	//alert(jQuery(e.target).attr("id"));
    	var now = new Date();
    	var course_id = jQuery(".sfwd-mark-complete input[name='course_id']").val();
    	var obj = jQuery.parseJSON(localStorage.getItem(course_id));
    	var clock_out_time = moment(now).format('YYYY-MM-DD HH:mm:ss');
    	

		var data = {
			'action': 'lmswr_process_in_out_accumulate',
			'clock_in_time':obj,
			'course_id':course_id,
			'clock_out_time': clock_out_time
		};
		
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			localStorage.removeItem(response);
		});
	}

});


jQuery(".dialog-btn .dialog-btn-cancel").text("OK");
		

});		