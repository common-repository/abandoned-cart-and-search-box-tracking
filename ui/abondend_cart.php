<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
global $wpdb;
$plugin_settings_data = array();
if(isset($_POST['hit_product_modal_settings_save']))
{
	$plugin_settings_data['hit_enable_ac_option'] = isset($_POST['hit_enable_ac_option']) ? 'yes' : 'no';
	$plugin_settings_data['hit_track_days'] = isset($_POST['hit_track_days']) ? sanitize_text_field( $_POST['hit_track_days'] ) : '';

	
	update_option('hit_stacks_ab_cart',$plugin_settings_data);
	echo '<div class="alert">'.__('Settings saved Sucessfully. ( Thanks for beign Customer with us - For more Bussinus upsell plugins, Get Touch with Us )','wp-sit-stack'). '</div>';
}
if(isset($_POST['hit_downlaod_ab_cart_data'])){
	$hit_from_month = isset($_POST['hit_from_month']) ? sanitize_text_field($_POST['hit_from_month']) : '';
	if($hit_from_month){
		$hit_from_month = date($hit_from_month.'-',time());
		$hit_from_year = date('-Y',time());
		$csv_export = array();
		  $colums[0] = "Name";  
		  $colums[1] = "Email Address";  
		  $colums[2] = "Country";  
		  $colums[3] = "Cart Price";  
		  $colums[4] = "Products & qty";
		  $csv_export[] = $colums;
		
		$sb_cart_data = get_option('hit_stacks_ab_cart');
			
		if(!empty($sb_cart_data) && isset($sb_cart_data['hit_enable_ac_option']) && $sb_cart_data['hit_enable_ac_option'] == 'yes'){
			$sql = 'SELECT u.display_name,u.user_email,u.ID,um.meta_value FROM '.$wpdb->prefix.'users u JOIN '.$wpdb->prefix.'usermeta um ON u.ID = um.user_id where (um.meta_key="hit_cart_track" and um.meta_value <> "" and um.meta_value like "%'.$hit_from_month.'%" and um.meta_value like "%'.$hit_from_year.'%")';
			$results = $wpdb->get_results($sql);
			$cart_values = array();
			if(!empty($results)){
				foreach ( $results as $key => $value ) {
			    	$meta_data = json_decode($value->meta_value,true);
			    	$total_price = 0;
			    	$full_products = '';
			    	foreach ($meta_data as $single) {
			    		$product = wc_get_product( $single['p_id'] );
			    		$full_products .= 'Name: '. $product->get_title() .' Qty: '. $single['qty'] .' ';
			    		$total_price += $product->get_price() * $single['qty'];
			    	}
			    	$value->country =  get_user_meta( $value->ID, 'billing_country', true );
			    	$row[0] = $value->display_name; 
				    $row[1] = $value->user_email; 
				    $row[2] = $value->display_name;
				    $row[3] = $total_price;
				    $row[4] = $full_products;
				    $csv_export[] = $row;
			    }
				
			}
		}
		ob_end_clean();

		  $output = fopen("php://output", "w");
		  foreach ($csv_export as $row) {
		      fputcsv($output, $row);
		  }
		   header("Content-type: text/csv");
		  header("Content-Disposition: attachment; filename=Cart_Details.csv");
		  //fclose($output);
		  exit();
	}

}
$plugin_settings_data = get_option('hit_stacks_ab_cart');
?>

<div style="width:100%;">
	<form method="post">
		<h3>Configure</h3>
		<table style="width:80%;font-size: 13px;">
			<tr valign="top">
				<td style="width:40%;font-weight:800;">
					<label for="hit_enable_ac_option"><?php _e('Enable/Disable','wp-sit-stack'); ?></label>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input " type="checkbox" name="hit_enable_ac_option" id="hit_enable_ac_option" style="" value="yes" <?php echo (isset($plugin_settings_data['hit_enable_ac_option']) && $plugin_settings_data['hit_enable_ac_option'] !='no') ? 'checked' : '' ?> placeholder=""> <?php _e('Use this Option!','wp-sit-stack'); ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<td style="width:40%;font-weight:800;">
					<label for="hit_track_days"><?php _e('How many days Old carts have to fetch?','wp-sit-stack'); ?></label>
				</td>
				<td scope="row" name="hit_track_days" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input " type="number" name="hit_track_days" id="hit_track_days" value="<?php echo (isset($plugin_settings_data['hit_track_days'])) ? $plugin_settings_data['hit_track_days'] : '' ?>" placeholder="Days count ex: 30">
					</fieldset>
				</td>
				<td></td>
			</tr>
			
			<tr valign="top">
				<td style="text-align: right;padding-right: 10px;" colspan="3">
					<button type='submit' name="hit_product_modal_settings_save" class="button button-primary"><?php _e('Save Changes','wp-sit-stack'); ?></button>
				</td>
				
			</tr>
		</table>
		
	</form>
	<form method="post">
		<h3>Download Cart Data (.csv)</h3>
		<table style="width:80%;font-size: 13px;">
			<tr valign="top">
				<td style="width:40%;font-weight:800;">
					<label for="hit_track_days"><?php _e('Enter the data from date to date','wp-sit-stack'); ?></label>
				</td>
				<td scope="row" name="hit_track_days" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<select name="hit_from_month" class="input-text regular-input">
							<option selected value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					</fieldset>
				</td>
				<td></td>
			</tr>
			
			<tr valign="top">
				<td style="text-align: right;padding-right: 20px;" colspan="3">
					<button type='submit' name="hit_downlaod_ab_cart_data" class="button button-primary"><?php _e('Downlaod','wp-sit-stack'); ?></button>
				</td>
				
			</tr>
		</table>
		
	</form>
</div>
<br/>
<?php 
