jQuery(document).ready(function(){
	var player_details = "https://api.radioking.io/widget/radio/radio-direct-impact/track/current";

		jQuery.get( player_details, function( data ) {

		var URL="https://listen.radioking.com/radio/66913/stream/104650";
		var TEXT = "I am listening song ("+data.title+") at radiodirectimpact.com station";
		document.getElementById('twitter_btn').addEventListener('click', function(){
		    window.open('http://twitter.com/share?url='+ URL +'&text='+ TEXT, 'sharer', 'toolbar=0,status=0,width=626,height=436');
		});

	  	jQuery("#jtitle").text(data.title);
	  	jQuery("#jartist").text(data.artist);
	  	//jQuery("#jalbum").text(data.album);

		new jPlayerPlaylist({
				jPlayer: "#jquery_jplayer_1",
				cssSelectorAncestor: "#jp_container_1",

			}, [
				{
					title:data.title,
					artist:data.artist,
					mp3:"https://listen.radioking.com/radio/66913/stream/104650",
					poster: data.cover
				}
				
			], {
				swfPath: mine_obj.jplayer+"/assets/dist/jplayer/jquery.jplayer.swf",
				supplied: "mp3",
				useStateClassSkin: true,
				wmode: "window",
				autoBlur: false,
				playlistOptions: {
    				autoPlay: true
  				},	
				smoothPlayBar: true,
				keyEnabled: true,
				audioFullScreen: false,
			  	size: {
				    width: "55px",
				    height: "60px"
				},
				cssSelector: {
	                play: "#play",
	                pause: "#pause"
        		},
        		volume: 0.5
			});
		});

	jQuery(".jp-volume-bar").slider({
        orientation: "horizontal",
        slide: function( event, ui ) {
            var volume = ui.value / 100;
            jQuery("#jquery_jplayer_1").jPlayer("volume", volume);
        }
    });


	/*jQuery(".jp-volume-max").click(function(){
		jQuery('.jp-volume-max').hide();
	})*/


if(jQuery("#jquery_jplayer_1").jPlayer("getData","diag.isPlaying") == true){
    //Is Playing
}



window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
    //document.getElementById("jp_container_1").style.height = "50px";
    jQuery("#jp_container_1").css({"height":"50px"});
    jQuery("#jquery_jplayer_1").css({"height":"40px","width":"40px","margin-top":"-5px"});
    jQuery("#jquery_jplayer_1 img").css({"height":"40px","width":"40px"});
    jQuery("#jp_container_1 .cssbutton").css({"margin-top":"-18px"});
    jQuery("#jp_container_1 .wrap_descript").css({"margin-top":"-6px"});
    jQuery("#jp_container_1 .wrap_descript2").css({"margin-top":"3px"});
    jQuery("#jp_container_1 .wrap_descript3").css({"margin-top":"2px","width":"30%"});
    jQuery(".jp-volume-controls").addClass("mathekha");
   	jQuery("#jalbum").hide();
   	jQuery(".wrap_descript3").css("width","26%");
    
  } else {
  	jQuery("#jp_container_1").css({"height":"80px"});
    jQuery("#jquery_jplayer_1").css({"height":"55px","width":"60px","margin-top":"3px"});
    jQuery("#jquery_jplayer_1 img").css({"height":"55px","width":"60px"});
    jQuery("#jp_container_1 .cssbutton").css({"margin-top":"-2px"});
    jQuery("#jp_container_1 .wrap_descript").css({"margin-top":"10px"});
    jQuery("#jp_container_1 .wrap_descript2").css({"margin-top":"18px"});
    jQuery("#jp_container_1 .wrap_descript3").css({"margin-top":"20px"});
   	jQuery(".jp-volume-controls").removeClass("mathekha");
    jQuery("#jalbum").show();
  }
}
//localStorage.removeItem("like");
if (localStorage.like !== null) {
	jQuery(".like_dislike").css("cursor","pointer");
	jQuery('.like_dislike').attr("disabled","disabled");// is not working with anchor tag
	jQuery(".like_dislike").hide(); //this is working
	jQuery(".like_dislike").after("<i class='fa fa-heart'></i>");
	jQuery(".fa-heart").css("color","#cc0101");
	
}


jQuery(".like_dislike").click(function(e){
	e.preventDefault();
	var count = jQuery(this).attr("count");
	
	data ={
		action:"like_dislike_calculator",
		'number':count
	};

	jQuery.post(mine_obj.ajaxurl, data, function(response){
		localStorage.setItem("like", "1");
		jQuery(".like_dislike").attr("disabled","disabled");
		jQuery(".like_dislike").hide(); //this is working
		jQuery(".like_dislike").after("<i class='fa fa-heart'></i>");
		jQuery(".fa-heart").css("color","#cc0101");
		jQuery(".wrap_descript3 .counter").html(response);
		alert("Thank to like our radio station");
	})

});


jQuery(".like_dislike").mouseenter(function() {
    	jQuery('.fa-heart-o').hide();		
    	jQuery(".fa-heart").show();	
    	jQuery(".fa-heart").css("color","#cc0101");
}).mouseleave(function() {
    	jQuery(".fa-heart-o").show();	
    	jQuery(".fa-heart").hide();	
});


});   

