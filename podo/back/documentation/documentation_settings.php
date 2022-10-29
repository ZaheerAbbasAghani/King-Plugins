<div class="wrap payment_details" style="background: #fff; padding: 10px 20px;clear: both;height: auto;overflow: hidden;">
<h1>Zahlungseinstellungen</h1> <hr>


<h3> Alle Zahlungsmethoden </h3>

<a href="#" style="float: right;" class="button button-primary add_payment_method"> Zahlungsmittel hinzufügen </a>

<form method="post" action="options.php">
    


<?php 

global $wpdb; 
$table_name = $wpdb->base_prefix.'anam_payment_methods';

$query = "SELECT * FROM $table_name";
$query_results = $wpdb->get_results($query);

    if(!empty($query_results)){
        echo '<table class="form-table">';
        foreach ($query_results as $result) { ?>
            <tr valign="top">
            <th scope="row" style="text-align: center;"> <img src="<?php echo $result->QRImage; ?>" style="width: 160px;"/></th>
            <td style="vertical-align: top;position: relative;padding-top: 40px;"><h1 style="padding-top: 0px;"> <?php echo $result->payment_method_name ?></h1>

            <?php if($result->enableQR == 1){ ?>
                <h4 style="margin: 8px 0px;"><?php echo $result->payment_method_description ?></h4>
            <?php }else{ ?>

             <h4 style="margin: 8px 0px;"><?php echo $result->payment_method_description ?></h4>

            <?php } ?>
                <a href="#" class="button button-default delete_payment_method" data-id="<?php echo $result->id ?>">Löschen</a>
               
            </td>

            </tr>

        <?php } ?>
         </table>
    <?php } else{ ?>
        <p>Es existiert keine Zahlungsmethode.</p>
    <?php } ?>
    

</form>
</div>