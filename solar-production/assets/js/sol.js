function initialize() {

var input = document.getElementById('search_loc');
var autocomplete = new google.maps.places.Autocomplete(input);
google.maps.event.addListener(autocomplete, 'place_changed', function () {

    //var place = autocomplete.getPlace();
    //document.getElementById('formatted_address').value = place.formatted_address;
});

}
google.maps.event.addDomListener(window, 'load', initialize);


jQuery(document).ready(function(){


jQuery("#select_package").bind('change', function() {
  var str = this.value;
  var num = str.split('-')[0];
  var pricing = str.split('-')[1].replace(/\s/g, '');
  var result = pricing.replace(/\s+/g, '')
  jQuery("#area1").val(num);
  jQuery("#pricing").val(result);

});

jQuery("#searchLocation").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    jQuery("#searchLocationSubmit").val("Finding...");

    var formatted_address = jQuery("#search_loc").val();
    var area1 = jQuery("#area1").val();
    var area2 = jQuery("#area2").val();
    var area3 = jQuery("#area3").val();
    var ratioValue = jQuery("#ratioValue").val();
    var dirValue = jQuery("#dirValue").val();


    var data1 = {
       'action': 'sol_searched_location_process',
        dataType: 'json',
       'address':formatted_address,
       'area1':area1,
       'area2':area2,
       'area3':area3,
       'ratioValue':ratioValue,
       'dirValue':dirValue,
    };



  	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

  	jQuery.post(ajax_object.ajax_url, data1, function(response) {
      
  var obj =JSON.parse(response);
if(obj.dia_values != ""){
      jQuery(".error_message").remove();
      jQuery(".maincontainer").show();
    
  var ctx = document.getElementById("myChart");

  var gcolor = ajax_object.graphColor;
  var graphMonthColorFont = ajax_object.graphMonthColorFont;
  var graphMonthFontSize = ajax_object.graphMonthFontSize;
var data = {
  labels: obj.month,
  datasets: [{
    data: obj.dia_values,
    backgroundColor: gcolor
  }]
}
var myChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
    "hover": {
      "animationDuration": 0
    },
    "responsive":true,
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x, bar._model.y + 15 );
          });
        });
      }
    },
    legend: {
      "display": false,
      labels: {
          fontColor: "blue",
          fontSize: 18
      }
    },
    tooltips: {
      "enabled": false
    },
    scales: {
      yAxes: [{
        display: false,
        gridLines: {
          display: false
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: false,
          beginAtZero: true,
           fontColor: "green",
          fontSize: 18
        }
      }],
      xAxes: [{
        gridLines: {
          display: false
        },
        ticks: {
          fontColor: graphMonthColorFont,
          fontSize: graphMonthFontSize,
          stepSize: 1,
          beginAtZero: true
        }
      }]
    }
  }
});

  var sum = parseInt(jQuery("#pricing").val()) / parseInt(obj.full.yearly_saving_1);

  jQuery("h1").remove();
  jQuery("h3").remove();
  jQuery("p").remove();
  jQuery(".chartbox").remove();

 jQuery(".maincontainer").before('<div class="chartHeadings"><h1>  Så här mycket solel kan du få från ditt tak. </h1> <h3>Sammanställning</h3> <p>(kWh)</p></div>');
  

var formatted_address = jQuery("#search_loc").val();
var area1 = jQuery("#area1").val();
var area2 = jQuery("#area2").val();
var area3 = jQuery("#area3").val();
var ratioValue = jQuery("#ratioValue").val();
var dirValue = jQuery("#dirValue").val();


    var output_url = document.location.origin+"/?print_all=1&address="+encodeURIComponent(formatted_address)+"&area1="+area1+"&area2="+area2+"&area3="+area3+"&ratioValue="+ratioValue+"&dirValue="+dirValue+"&pricing="+jQuery("#pricing").val();

 jQuery(".maincontainer").after("<div class='chartbox'><div class='storlek'><p class='label'> Kvadratmeter </p><p class='val'>"+obj.m2+"</p></div><div class='box2'><p class='label'> Storlek på anläggningen i kWp </p><p class='val'>"+jQuery("#area1").val()+"</p></div><div class='box3'><p class='label'> Investering efter grön ROT i kr </p><p class='val'>"+jQuery("#pricing").val()+"</p></div><div class='box4'><p class='label'> Årsproduktion i kWh </p><p class='val'>"+obj.full.yearly_production_1+"</p></div><div class='box5'><p class='label'> Besparing per år i kr </p><p class='val'>"+obj.full.yearly_saving_1+"</p></div><div class='box6'><p class='label'> Återbetalningstid i antal år </p><p class='val'>"+sum.toFixed(3) +"</p></div><a href='"+output_url+"' style='display: block;background: #ddd;padding: 10px 10px;width: 25%;margin: 50px auto 0px auto;border-radius:5px;' id='btn-Preview-Image' target='_blank'> Download </a> </div>");
  jQuery("#searchLocationSubmit").val("Submit");





  var boxColor = ajax_object.boxColor;
  jQuery(".chartbox").find("div").css("background",boxColor);

  var outputBoxTextColors = ajax_object.outputBoxTextColors;
  var BoxFontSize = ajax_object.BoxFontSize;

  jQuery(".chartbox").find("p").css("color",outputBoxTextColors);
  jQuery(".chartbox").find("p").css("font-size",BoxFontSize+"px");
  var export_status = ajax_object.export;
  if(export_status == 1){
    jQuery("#btn-Preview-Image").remove();
  }


}else{
  jQuery(".maincontainer").before('<h1 class="error_message">  Solkollen is only providing calculations for adresses within Sweden</p>');
  jQuery(".maincontainer").hide();
  jQuery(".chartHeadings").remove();
  jQuery(".chartbox").remove();
}

});

});
});