jQuery(document).ready(function(){

jQuery(window).keydown(function(event){
  if(event.keyCode == 13) {
    event.preventDefault();
    return false;
  }
});


var tags = jQuery('#candidates').inputTags({
tags: [],

  init: function(elem) {
    jQuery('span', '#events').text('init');
  //  jQuery('<p class="results">').html('<strong>Tags:</strong> ' + elem.tags.join(' - ')).insertAfter(elem.jQuerylist);
  },
  create: function() {
    jQuery('span', '#events').text('create');
  },
  update: function() {
    jQuery('span', '#events').text('update');
  },
  destroy: function() {
    jQuery('span', '#events').text('destroy');
  },
  selected: function() {
    jQuery('span', '#events').text('selected');
  },
  unselected: function() {
    jQuery('span', '#events').text('unselected');
  },
  change: function(elem) {
    jQuery('.results').empty().html('<strong>Tags:</strong> ' + elem.tags.join(' - '));
  },

});


  var i = 1;
  jQuery('#add').click(function(){
      i++;
      jQuery('#dynamic_field').append('<tr id="row'+i+'"><td><div class="field-wrap"><input type="text" name="camp_voter_phone[]" placeholder="Enter Voter Phone" id="camp_voter_phone_'+i+'" style="width:86%;" class="camp_voter_phone"/><button name="remove" id="'+i+'" class="button button-default btn_remove">X</button></div></td></tr>');
  });

  jQuery(document).on('click','.btn_remove', function(){
      var button_id = jQuery(this).attr("id");
      jQuery("#row"+button_id+"").remove();
  });


  jQuery(document).on('click','.delete_row', function(e){
      
      e.preventDefault();

      if(confirm('Are you sure?')) {

        jQuery(this).parent().parent().css("background","red");
        var name = jQuery(this).parent().parent().find("td.username").eq(0).text();
        var id = jQuery(this).parent().parent().attr("data-id");
        
        var data = {
          'action': 'camp_delete_user',
          'phone': name,
          'id':id,
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(camp_ajax_object.ajax_url, data, function(response) {
            alert(response);
            location.reload();  

        });
      }

      else{
        return false;
      }
  });


jQuery(document).on("input",".camp_voter_phone", function(){

    var val = jQuery(this).val();
    var post_id  = jQuery("#dynamic_field").attr("data-id");
    var id  = jQuery(this).attr("id");

    var data = {
      'action': 'camp_check_if_phone_exists',
      'val': val,
      'post_id':post_id,
      'id':id,
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(camp_ajax_object.ajax_url, data, function(response) {
        if(response.status == 1){
          console.log(response);
          alert("Number Already Exists");
          jQuery('#'+response.id).val("");
        }else{
          console.log();
        }
      
    });
});



  


});