jQuery(document).ready(function(){

	var startDate = jQuery(".startDate").text();
	jQuery('#simple_timer').syotimer({
	  date: new Date(startDate),
	});


	jQuery(document).on("click",".campVote", function(e){

		e.preventDefault();
		
		var candidate = jQuery(this).parent().find("p").text();
		var post_id = jQuery(this).parent().parent().attr("data-id");

		var data = {
			'action': 'campaign_votes',
			'candidate': candidate,
			'post_id':post_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(camp_ajax_object.ajax_url, data, function(response) {
			alert(response);
			location.reload();
		});

	});


	var votes = camp_ajax_object.Votes;


	var candidate = [];
	var total_vote = [];

	jQuery.each( votes, function( index, object ){
	  	candidate.push(object.candidate.toUpperCase());
	});

	jQuery.each( votes, function( index, object ){
	  	total_vote.push(object.vote);
	});

	var xValues = candidate;
	var yValues = total_vote;

	var chart = new Chart("campaignCanvas", {
	type: 'bar',
	data: {
	  labels: xValues,
	  datasets: [{
	     data: yValues,
	     backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff','#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'],
	     borderColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff','#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff']
	  }]
	},
	options: {
	  scales: {
	    yAxes: [{
	        ticks: {
	            beginAtZero: true
	        }
	    }]
	},
	legend: {
	 labels: {
	    generateLabels: function(chart) {
	       var labels = chart.data.labels;
	       var dataset = chart.data.datasets[0];
	       var legend = labels.map(function(label, index) {
	          return {
	             datasetIndex: 0,
	             text: label+" - "+dataset.data[index],
	             fillStyle: dataset.backgroundColor[index],
	             strokeStyle: dataset.borderColor[index],
	             lineWidth: 1
	          }
	       });
	       return legend;
	    }
	 }
	}
	}
	});


	if(jQuery(".candidate_list li").hasClass("voted")){
		jQuery(".candidate_list li.voted a").attr("class", "");
	}
	

});