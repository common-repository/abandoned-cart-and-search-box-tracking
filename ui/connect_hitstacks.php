<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
$plugin_settings_data = array();
if(isset($_POST['hittech_connect_save']))
{
	$plugin_settings_data['hit_enable_key_option'] = isset($_POST['hit_enable_key_option']) ? 'yes' : 'no';
	$plugin_settings_data['hit_key'] = isset($_POST['hit_key']) ? sanitize_text_field($_POST['hit_key']) : '';
	
	update_option('hit_connect_stacks',$plugin_settings_data);
	echo '<div class="alert">'.__('Settings saved Sucessfully. ( Thanks for beign Customer with us - For more Bussinus upsell plugins, Get Touch with Us )','wp-sit-stack'). '</div>';
}

$plugin_settings_data = get_option('hit_connect_stacks');
?>
<style>
	
/**
 *  hittech BOX
 *  Used all over on the admin, a structure with a deep blue header and
 *  a white content area
 */

.hittech-box-title-bar {
	background: #d4d4d4;
	padding: 16px;
	display: block;
}

.hittech-box-title-bar h3 {
	color: black;
	font-weight: 400;
	margin: 0;
	font-size: 16px;
	padding: 0;
}

.hittech-box-title-bar__small {
	padding: 11px 16px;
}

.hittech-box-title-bar__small h3 {
	font-size: 16px;
}

.hittech-box-content {
	background: #fff;
	padding: 22px;
	border: 1px solid #e4e4e4;
	border-top: 0;
	min-height: 130px;
}

</style>
<div style="width:100%;">
	<div class="hittech-box" style="margin:20px 10px 50px 10px;">
		<div class="hittech-box-title-bar">
			<h3>Connect HITStacks - For Sent Automated Emails, SMS.</h3>
		</div>
		<div class="hittech-box-content">
			<p style="font-size:15px;">HITStacks is fully <b>automated abondend cart & User Search tracking</b> system. Our system is automatically track data from your site & engage/remind the customers about the cart by SMS, Email's. By this, you can <b>ingress the traffic, sales%</b> quickly.</p>
			<a href="#" style="float:right;padding: 0.625rem 1.125rem;font-size: 16px;line-height: 1.5;border-radius: 0.25rem;color: #fff;background-color: #377dff;border-color: #377dff;text-decoration: unset;" class="btn btn-primary">Create Free Account or Choose Best Plan</a>
		</div>
	</div>
	<form method="post">
		<h3>Connect</h3>
		<table style="width:80%;font-size: 13px;">
			<tr valign="top">
				<td style="width:40%;font-weight:800;">
					<label for="hit_enable_key_option"><?php _e('Enable/Disable','wp-sit-stack'); ?></label><br/>
					<small style="color:green">Your Data is very Protected and this is not visible anywhere.</small>
				</td>
				<td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input " type="checkbox" name="hit_enable_key_option" id="hit_enable_key_option" style="" value="yes" <?php echo (isset($plugin_settings_data['hit_enable_key_option']) && $plugin_settings_data['hit_enable_key_option'] !='no') ? 'checked' : '' ?> placeholder=""> <?php _e('I agree to connect my abondend cart, SMS emails with HIT Stacks!','wp-sit-stack'); ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<td style="width:40%;font-weight:800;">
					<label for="hit_track_days"><?php _e('Key Created from hitstacks integratiosn page.','wp-sit-stack'); ?></label>
				</td>
				<td scope="row" name="hit_track_days" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
					<fieldset style="padding:3px;">
						<input class="input-text regular-input " type="text" name="hit_key" id="hit_key" value="<?php echo (isset($plugin_settings_data['hit_key'])) ? $plugin_settings_data['hit_key'] : '' ?>" placeholder="Paste Key">
					</fieldset>
				</td>
				<td></td>
			</tr>
			
			<tr valign="top">
				<td style="text-align: right;padding-right: 10px;" colspan="3">
					<button type='submit' name="hittech_connect_save" class="button button-primary"><?php _e('Save Changes','wp-sit-stack'); ?></button>
				</td>
				
			</tr>
		</table>
		
	</form>
</div>
<br/>
<?php 
