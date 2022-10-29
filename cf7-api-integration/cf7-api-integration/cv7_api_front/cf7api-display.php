<?php 
function cf7api_display_all(){

//SELECT * FROM "2521118" ORDER BY id DESC LIMIT 1 
global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}quoteapi ORDER BY id DESC LIMIT 1", ARRAY_A );
//print_r($results);
?>
<div class="post-content">
				<div class="fusion-fullwidth fullwidth-box fusion-builder-row-1 nonhundred-percent-fullwidth non-hundred-percent-height-scrolling">
				<div class="fusion-builder-row fusion-row ">
				<div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_1 fusion-builder-column-0 fusion-one-full fusion-column-first fusion-column-last 1_1" ><div class="fusion-column-wrapper"  data-bg-url=""><div class="fusion-title title fusion-title-1 fusion-title-center fusion-title-text fusion-title-size-one" ><div class="title-sep-container title-sep-container-left"><div class="title-sep sep-single sep-dashed" ></div></div>
				<h1 class="title-heading-center fusion-responsive-typography-calculated" data-fontsize="72" data-lineheight="72px"><p style="text-align: center;">YOUR ESTIMATE</p></h1>
				<div class="title-sep-container title-sep-container-right">
				<div class="title-sep sep-single sep-dashed" style="border-color:#f46f72;">
				</div></div></div><div class="fusion-sep-clear"></div>
				<div class="fusion-separator fusion-full-width-sep sep-shadow" style="background:radial-gradient(ellipse at 50% -50% , #e0dede 0px, rgba(255, 255, 255, 0) 80%) repeat scroll 0 0 rgba(0, 0, 0, 0);background:-webkit-radial-gradient(ellipse at 50% -50% , #e0dede 0px, rgba(255, 255, 255, 0) 80%) repeat scroll 0 0 rgba(0, 0, 0, 0);background:-moz-radial-gradient(ellipse at 50% -50% , #e0dede 0px, rgba(255, 255, 255, 0) 80%) repeat scroll 0 0 rgba(0, 0, 0, 0);background:-o-radial-gradient(ellipse at 50% -50% , #e0dede 0px, rgba(255, 255, 255, 0) 80%) repeat scroll 0 0 rgba(0, 0, 0, 0);margin-left: auto;margin-right: auto;margin-top:;margin-bottom:50px;"></div><div class="fusion-clearfix"></div></div></div><div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2 fusion-builder-column-1 fusion-one-half fusion-column-first 1_2" style="margin-top:0px;margin-bottom:0px;width:50%;width:calc(50% - ( ( 4% ) * 0.5 ) );margin-right: 4%;"><div class="fusion-column-wrapper" style="padding: 0px; background-position: left top; background-repeat: no-repeat; background-size: cover; height: auto;" data-bg-url="">
				<div class="fusion-text">

<?php foreach ($results  as $value) { 

	//print_r($value['dis_prl']);
	?>
	<table>
	    <tr><td> 	LOW = <?php echo $value['dis_prl']; ?></td></tr>
		<tr><td> 	SIZE = <?php echo $value['min_kw'];?></td></tr>
		<tr><td> 	HIGH = <?php echo $value['dis_prh'];?></td></tr>
		<tr><td> 	SIZE = <?php echo $value['max_kw'];?></td></tr>
		<tr><td>	25 PRODUCTION = <?php echo $value['prod_25'];?></td></tr>
		<tr><td>	CO2 = <?php echo $value['carb_dio'];?></td></tr>
		<tr><td>	CO2 OFFSET = <?php echo $value['co2_offset'];?></td></tr>
		<tr><td>	CARS OFF = <?php echo $value['cars_off'];?></td></tr>
		<tr><td>	TREES PLANTED = <?php echo $value['tree_equiv'];?></td></tr>
		<tr><td>	WATER SAVED = <?php echo $value['water_saved'];?></td></tr>
		<tr><td>	AREA = <?php echo $value['area'];?></td></tr>
		<tr><td>	HOURS = <?php echo $value['hours'];?></td></tr>
		<tr><td>	INPUT BILL = <?php echo $value['input_bill'];?></td></tr>
		<tr><td>	System Size = <?php echo $value['needed_cap'];?></td></tr>
		<tr><td>	Avoided Bill = <?php echo $value['avo_bi_25'];?></td></tr>
		<tr><td>	25-Year Production = <?php echo $value['depprod_25'];?></td></tr>
		<tr><td>    Savings = <?php echo $value['savings'];?></td></tr>
		<tr><td>	25-Year ROI = <?php echo $value['simple_roi'];?></td></tr>
		<tr><td>	INCENTIVES = <?php echo $value['smart'];?></td></tr>
		<tr><td>	Year 1 Production = <?php echo $value['yr1_prod'];?></td></tr>
			
	</table>
<?php } ?>
				</div>
<div class="fusion-clearfix"></div></div></div><div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2 fusion-builder-column-2 fusion-one-half fusion-column-last 1_2" style="margin-top:0px;margin-bottom:0px;width:50%;width:calc(50% - ( ( 4% ) * 0.5 ) );"><div class="fusion-column-wrapper" style="padding: 0px; background-position: left top; background-repeat: no-repeat; background-size: cover; height: auto;" data-bg-url=""><div class="fusion-text"><p><img src="<?php echo $value['image_url'];?>"></p>
</div><div class="fusion-clearfix"></div></div></div></div></div>
							</div>

<?php }

add_shortcode("cf7api_display", "cf7api_display_all");

 ?>