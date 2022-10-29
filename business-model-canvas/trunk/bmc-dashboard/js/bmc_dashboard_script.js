jQuery(document).ready(function() {

  jQuery(".columns").niceScroll({cursorcolor:"#0000004d"});

  jQuery('#create_sticky_notes').click(function(e){ 
     	e.preventDefault();
  var fields = new Array();
  //console.log(fields);
  var i;
  // Get all text areas.
  jQuery('#canvas_form textarea').each(function(){
      
     if(jQuery(this).val()==""){
      alert("Fill All the fields");
      return false;
    }


     // get the value
      var name  = jQuery(this).attr("name");
      var value = jQuery(this).val();
      var container = jQuery(this).attr("data-container");
      var color = jQuery(this).attr("data-color");
      //Pushing fields
      fields.push({"name":name, "value":value,"container":container,"color":color});
     
  });

  //console.log(fields);
	var data={
		'action':'bmc_create_sticky_notes_by_textarea',
    'ajax_nonce': ajax_object.nonce,
		'fields':fields
  };
//alert(ajax_object.ajax_url);
  jQuery.post(ajax_object.ajax_url, data, function(response) {
	  console.log(response);
    location.reload(); 
	});

});



jQuery(".addsticky").click(function(){
    //var newDiv = jQuery("#key_partners");
  
  var newDiv = jQuery(this).parent().parent().attr("id");
  var gc = jQuery(this).parent().parent().attr("class").split(' ').pop();
  //console.log(gc);

  var ran_txt = Math.random().toString(36).substr(2, 5);
  var textArea = jQuery('<div class="stickynote"><span class="delete" id="1">x</span><textarea class="stickyValues" name="'+ran_txt+'" data-container="'+gc+'" data-color="" required></textarea>  <input type="text" class="my-color-field" value="#fffa90"/></div>'); 
    jQuery(this).closest("#"+newDiv).append(textArea);
    jQuery('.my-color-field').wpColorPicker({

     change: function(event, ui){
      var changeColor = jQuery(this).val();

      var otherInput = jQuery(this).closest('.stickynote');
      var dcontainer = jQuery(this).closest('.stickynote').find('.stickyValues');
      jQuery(otherInput).css("background-color",changeColor);
      jQuery(dcontainer).attr('data-color' , changeColor)

     },

    });


});




// Delete row 

jQuery(".delete").click(function(){
  var ID = jQuery(this).attr('data-id');
  jQuery(this).parent().fadeOut( "slow" );
  //console.log(ID);
  var data={
    'action':'bmc_delete_sticky_notes',
    'ajax_nonce': ajax_object.nonce,
    'id':ID
  };

  jQuery.post(ajax_object.ajax_url, data, function(response) {
    //console.log(response);
    location.reload(); 
  });


});

// Editable Sticky


jQuery(".stickyValues").dblclick(function(){

jQuery(this).parents('.key_activities').css("overflow","auto");

var name = jQuery(this).attr("data-name");
var divHtml = jQuery(this).html();
var editableText = jQuery("<textarea data-name="+name+" data-color='' class='stickyValues'></textarea>  ");
editableText.val(divHtml);
jQuery(this).replaceWith(editableText);
editableText.focus();
jQuery(editableText).after("<a href='#' class='btn btn-sm btn-success updateStickynote'><i class='fa fa-thumbs-up' aria-hidden='true'></i></a> <input type='text' class='clr' value='#fffa90'/>");



jQuery('.clr').wpColorPicker({

  change: function(event, ui){
  var changeColor = jQuery(this).val();

  var otherInput = jQuery(this).closest('.stickynote');
  var dcontainer = jQuery(this).closest('.stickynote').find('.stickyValues');
  jQuery(otherInput).css("background-color",changeColor);
  jQuery(dcontainer).attr('data-color' , changeColor)

  },

});



});




// Update Sticky Note

jQuery('.stickynote').on('click', '.updateStickynote', function (e){
  e.preventDefault();
  var field_attr = jQuery(this).parents(".stickynote").find('textarea').attr('data-name');
  var field_val = jQuery(this).parents(".stickynote").find('textarea').val();
  var field_col = jQuery(this).parents(".stickynote").css('background-color');
  //alert(field_col);
  var ID = jQuery(this).attr('data-id');
  
  //console.log(ID);
  var data={
    'action':'bmc_update_field_by_unique_id',
    'ajax_nonce': ajax_object.nonce,
    'field_attr':field_attr,
    'field_val':field_val,
    'field_col':field_col
  };

  jQuery.post(ajax_object.ajax_url, data, function(response) {
    console.log(response);
    location.reload(); 

  });



});



});