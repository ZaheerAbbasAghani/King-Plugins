jQuery(document).ready(function(jQuery) {

  jQuery(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });


    var tags = jQuery('#tags').inputTags({
      tags: [],
      autocomplete: {
        values: ['Small Package', 'Big Package']
      },
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
      autocompleteTagSelect: function(elem) {
        console.info('autocompleteTagSelect');
      }
    });

   /* jQuery('#tags').inputTags('tags', 'habitant', function(tags) {
      jQuery('.results').empty().html('<strong>Tags:</strong> ' + tags.join(' - '));
    });*/

    var autocomplete = jQuery('#tags').inputTags('options', 'autocomplete');
    jQuery('span', '#autocomplete').text(autocomplete.values.join(', '));

  });