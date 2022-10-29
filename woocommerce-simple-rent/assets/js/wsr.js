jQuery(document).ready(function(){

jQuery("#from_date").attr("disabled", true);
//jQuery("#to_date").attr("disabled", true);
//jQuery("#to_date").attr("readonly", true);

jQuery(document).on('change', '.variation-radios input', function() {
  jQuery('select[name="'+jQuery(this).attr('name')+'"]').val(jQuery(this).val()).trigger('change');
   jQuery(".variation-radios label").css("border","1px solid #ddd");
   jQuery(this).next("label").css("border","1px solid #000");
    
    jQuery("#from_date").attr("disabled", false);
   // jQuery("#to_date").attr("disabled", false);
});
jQuery(document).on('woocommerce_update_variation_values', function() {
  jQuery('.variation-radios input').each(function(index, element) {
    jQuery(element).removeAttr('disabled');
    var thisName = jQuery(element).attr('name');
    var thisVal  = jQuery(element).attr('value');
    if(jQuery('select[name="'+thisName+'"] option[value="'+thisVal+'"]').is(':disabled')) {
      jQuery(element).prop('disabled', true);
    }

  });
});




jQuery( "#from_date" ).datepicker({
defaultDate: "+1w",
changeMonth: true,
 dateFormat: "dd-mm-yy",
 minDate: 2,
numberOfMonths: 1,
onSelect: function( selectedDate ) {
    if(this.id == 'from_date'){
     var a = jQuery(".variation-radios input[name='attribute_duration']:checked").val();
      var increase = a.split(" ")[0];
      var dateMin = jQuery('#from_date').datepicker("getDate");
      var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1); 
      var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + parseInt(increase)); 
    jQuery('#to_date').val(jQuery.datepicker.formatDate('dd-mm-yy', new Date(rMax)));                    
    }
    
}
});


//alert(jQuery(".woocommerce-order-received .wc-item-meta li").eq(0).find("p").text());


});