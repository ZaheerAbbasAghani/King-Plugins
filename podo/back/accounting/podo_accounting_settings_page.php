<div class="wrap" style="background: #fff;padding: 10px 20px;">
<h1> Buchhaltungseinstellungen </h1><hr>

<div id="tabs">
  <ul>
    <li><a href="#overview">Overview</a></li>
    <li><a href="#revenue">Umsatz</a></li>
    <li><a href="#pending">Ausstehende Zahlungen</a></li>
  </ul>
  <div id="overview">
    <h3>Totaler Umsatz</h3><hr>

		<?php 

		$monday = date('d.m.Y', strtotime('monday this week'));
		$sunday = date('d.m.Y', strtotime('sunday this week'));

		$first_date=date('d.m.Y',strtotime('first day of this month'));
		$last_date = date('d.m.Y',strtotime('last day of this month'));


		global $wpdb;
		$table_name = $wpdb->base_prefix.'anam_dokument_info';
		$currency = get_option('podo_currency');
		/* Today */
		$today = date('d.m.Y');
		$query ="SELECT sum(price) as pr FROM $table_name WHERE created_at >= CURDATE()";
		$daily = $wpdb->get_results($query);

		/* Week */
		$week = date("d.m.Y", strtotime("-1 week"));

		$query1 ="SELECT sum(price) as weekly_revenue FROM $table_name WHERE WEEKOFYEAR(date(created_at))=WEEKOFYEAR(CURDATE())";
		$weekly = $wpdb->get_results($query1);


		/* Monthly */
		$month = date("d.m.Y", strtotime("-1 month"));
		$query2 ="SELECT sum(price) as monthly_revenue FROM $table_name WHERE date(created_at) between  DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()";
		$monthly = $wpdb->get_results($query2);

		?>

		<ul>
			<li>
				<?php if(!empty($daily[0]->pr)){ ?>
					<span><?php echo $daily[0]->pr.' '.$currency; ?></span>
				<?php }else{ ?>
					<span><?php echo '0 '.$currency; ?></span>
				<?php } ?>

				<p> Heute - <?php echo $today; ?></p></li>

			<li>
				<?php if(!empty($weekly[0]->weekly_revenue)){ ?>
					<span> <?php echo $weekly[0]->weekly_revenue.' '.$currency; ?> </span>
				<?php }else{ ?>
					<span><?php echo '0 '.$currency; ?></span>
				<?php } ?>

				<p>Diese Woche, <?php echo $monday; ?> - <?php echo $sunday; ?>	</p></li>

			<li>
				<?php if(!empty($monthly[0]->monthly_revenue)){ ?>
					<span><?php echo $monthly[0]->monthly_revenue.' '.$currency; ?></span> 
				<?php }else{ ?>
					<span><?php echo '0 '.$currency; ?></span>
				<?php } ?>

				<p>Dieser Monat, <?php echo $first_date; ?> - <?php echo $last_date; ?></p></li>
			
			<?php $turnover = $monthly[0]->monthly_revenue / 30; ?>
			<li><h3 style="font-size: 1em;margin-top: 0px;">Durchschnittlicher Tagesumsatz </h3><span> <?php echo round($turnover, 2).' '.$currency; ?> </span><p>Dieser Monat, <?php echo $first_date; ?> - <?php echo $last_date; ?></p></li>
		</ul>


  </div><!-- First Tab -->
  <div id="revenue">
  
  <?php 
  	// Fetching records by created_at
	$query3 ="SELECT date(created_at) AS date_column,sum(price) as revenue FROM $table_name  GROUP BY date_column ORDER BY date_column DESC LIMIT 10";
	$revenue_by_created_at = $wpdb->get_results($query3, ARRAY_A);
	$customer_table = $wpdb->base_prefix.'anam_customer_info';


  ?>

<?php 
$arr = array();
foreach ($revenue_by_created_at as $value) {
	array_push($arr, $value['date_column']);
}

$months = array_unique(array_map(function($date) {
    return DateTime::createFromFormat('Y-m-d', $date)->format('F');
}, $arr));

$years = array_unique(array_map(function($date) {
    return DateTime::createFromFormat('Y-m-d', $date)->format('Y');
}, $arr));

?>



  	<ul>
  		<?php foreach ($revenue_by_created_at as $rev) { ?>
	  		<li style="font-weight:bold; margin:20px 0px"> <?php echo date("d.m.Y",strtotime($rev['date_column'])); ?> </li>
	  		<li style="font-weight:bold; margin:20px 0px"> <?php echo $rev['revenue'].' '.$currency; ?></li>
	  		<li style="font-weight:bold; margin:20px 0px">  Status</li>

	  		<?php
	  		// Fetching records day by day
			$query4 ="SELECT * FROM $table_name WHERE Date(created_at)= '".$rev['date_column']."' ";
			$revenue_by_day = $wpdb->get_results($query4);
/*	echo "<pre>";
	print_r($revenue_by_day);
	echo "</pre>";*/
			//print_r($revenue_by_day);
			foreach ($revenue_by_day as $rbd) { 

				// Get Username Only
				$query5 ="SELECT * FROM $customer_table WHERE id='".$rbd->customer_id."' ";
				$user = $wpdb->get_results($query5);
	
	  		 ?>
	  		 <div class="rowbyDate">
	  		  	<?php if(!empty($user[0]->first_name)){ ?>
		  			<li><?php echo $user[0]->first_name.' '.$user[0]->last_name; ?></li>
		  		<?php } ?>
		  			<li><?php echo $rbd->price.' '.$currency;?></li>
		  			<?php if($rbd->status == "paid"){ ?>
		  				<li><span class="dashicons dashicons-yes" style="color:#4ab516;font-size: 30px;line-height: 26px;margin-left: -6px;"></span></li>
		  			<?php } else { ?>
		  				<li><span class="dashicons dashicons-clock" style="color:#f7c631;font-size: 22px;line-height: 26px;"></span></li>
		  			<?php } ?>
		  	 </div>

	  		<?php } // end inner foreach ?>
			<hr style="margin-top: 30px;border: 1px solid #ddd;background:#646464;height: 1px;">
	  	<?php } // end outer foreach ?>

  		
  	</ul>

  </div><!-- Second Tab -->


  <div id="pending">
	    
	<?php 
	  	// Fetching records by created_at
		$query3 ="SELECT date(created_at) AS date_column,sum(price) as revenue FROM $table_name WHERE status='pending' GROUP BY date_column ORDER BY date_column DESC LIMIT 10";
		$revenue_by_created_at = $wpdb->get_results($query3);
		$customer_table = $wpdb->base_prefix.'anam_customer_info';

	  ?>
	    <div class="resetyear">
			<span class="dashicons dashicons-update"></span>
			<select name="filter_by_year" class="custom_filters">
				<option value="" selected disabled>Nach Jahr sortieren</option>
				<?php foreach ($years as $info1){ ?>
					<option value="<?php echo $info1; ?>"><?php echo $info1; ?></option>
				<?php } ?>
			</select>
		</div>

		<div class="resetMonth">
			<span class="dashicons dashicons-update"></span>
			<select name="filter_by_month" class="custom_filters">
				<option value="" selected disabled>Nach Monat sortieren</option>
				<?php foreach ($months as $info2){ ?>
					<option value="<?php echo $info2; ?>"><?php echo $info2; ?></option>
				<?php } ?>
			</select>
		</div>



  	<ul>
  		<?php foreach ($revenue_by_created_at as $rev) { ?>
	  		<li style="font-weight:bold; margin:20px 0px" data-month="<?php echo date('F',strtotime($rev->date_column)); ?>"> <?php echo date("d.m.Y", strtotime($rev->date_column)); ?> </li>
	  		<li style="font-weight:bold; margin:20px 0px" data-month="<?php echo date('F',strtotime($rev->date_column)); ?>"> <?php echo $rev->revenue.' '.$currency; ?></li>
	  		<li style="font-weight:bold; margin:20px 0px" data-month="<?php echo date('F',strtotime($rev->date_column)); ?>">  Status</li>
	  		<li style="font-weight:bold; margin:20px 0px" data-month="<?php echo date('F',strtotime($rev->date_column)); ?>">  Zahlungsstatus ändern </li>

	  		<?php
	  		// Fetching records day by day
			$query4 ="SELECT * FROM $table_name WHERE Date(created_at)= '".$rev->date_column."' AND  status='pending' ";
			$revenue_by_day = $wpdb->get_results($query4);

			//print_r($revenue_by_day);
			foreach ($revenue_by_day as $rbd) { 

				// Get Username Only
				$query5 ="SELECT * FROM $customer_table WHERE id='".$rbd->customer_id."' ";
				$user = $wpdb->get_results($query5);
	
	  		 ?>
	  		 <div class="rowbyDate" data-month="<?php echo date('F',strtotime($rbd->created_at)); ?>" data-year="<?php echo date('Y',strtotime($rbd->created_at)); ?>">
	  		  	<?php if(!empty($user[0]->first_name)){ ?>
		  			<li data-month="<?php echo date('F',strtotime($rbd->created_at)); ?>" data-year="<?php echo date('Y',strtotime($rbd->created_at)); ?>"><?php echo $user[0]->first_name.' '.$user[0]->last_name; ?></li>
		  		<?php } ?>
		  			<li data-month="<?php echo date('F',strtotime($rbd->created_at)); ?>" data-year="<?php echo date('Y',strtotime($rbd->created_at)); ?>"><?php echo $rbd->price.' '.$currency;?></li>
		  			<?php if($rbd->status == "paid"){ ?>
		  				<li data-month="<?php echo date('F',strtotime($rbd->created_at)); ?>" data-year="<?php echo date('Y',strtotime($rbd->created_at)); ?>"><span class="dashicons dashicons-yes" style="color:#4ab516;font-size: 30px;line-height: 26px;margin-left: -6px;"></span></li>
		  			<?php } else { ?>
		  				<li data-month="<?php echo date('F',strtotime($rbd->created_at)); ?>" data-year="<?php echo date('Y',strtotime($rbd->created_at)); ?>"><span class="dashicons dashicons-clock" style="color:#f7c631;font-size: 22px;line-height: 26px;"></span></li>
		  			<?php } ?>
		  			<li data-month="<?php echo date('F',strtotime($rbd->created_at)); ?>" data-year="<?php echo date('Y',strtotime($rbd->created_at)); ?>"> <a href="#" data-id="<?php echo $rbd->id;?>" class='change_status'> Status zu “bezahlt” ändern </a></li>
		  	 </div>

	  		<?php } // end inner foreach ?>
	  		<hr style="margin-top: 30px;border: 1px solid #ddd;background: #646464;height: 1px;">
	  	<?php } // end outer foreach ?>

  		
  	</ul>


  </div><!-- Third Tab -->
</div>



</div>