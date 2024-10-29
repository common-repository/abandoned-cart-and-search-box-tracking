<?php 
/**
 * Plugin Name: WC Missing Cart Handling & Order SMS Handling
 * Plugin URI: #
 * Description: Track the customer tracking data, track the Abandoned cart from shop tyhen remond them by email or SMS.
 * Author: HitStacks
 * Author URI: https://hitstacks.com
 * Version: 1.1.0
 * License: GPLv3
 * Text Domain: wp-sit-stack
 * Domain Path: /languages
 *
 * @package wp-migration-trulywp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


if(!class_exists("HITStacks_Class_Main")) {
	class HITStacks_Class_Main	{
		 public function __construct() {
			add_action('admin_menu',array($this,'hits_settings_page'));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'hit_product_ads_action_links'));
     
			//Future Write Update code here. get_option('twp_mu_plugins_update','1.2') ? override : define/skip
		}
		public function hit_product_ads_action_links($links) {
		    $plugin_links = array(
		      '<a href="' . admin_url('admin.php?page=hitstacks') . '" style="color:blue;">' . __('Setup', 'wp-sit-stack') . '</a>',
		      '<a href="#" style="color:blue;">' . __('Support', 'wp-sit-stack') . '</a>',
		      );
		    return array_merge($plugin_links, $links);
		 } 

		public function hits_settings_page() {
			
			$page = add_menu_page(
				'HIT Stacks',
				'HIT Stacks',
				'manage_options',
				'hitstacks',
				[
                    __CLASS__,
                    'pagecontents',
                ],
				plugin_dir_url(__FILE__).'wSQZiT5O.png',
				'1'
			);	
		}
		public static function pagecontents() {
			$tab = (!empty($_GET['subtab'])) ? esc_attr($_GET['subtab']) : 'abondend';
                echo '<hr>
						<center>
						  <h1 > '.__('Abondend Cart & Search Bar Tracking','wp-sit-stack').' <sma;</h1>
						  
						</center>

						<hr>
						<style type="text/css">
						  .alert {
						    padding: 13px;
						    background-color: #4CAF50; /* Green */
						    color: white;
						    margin: 10px;
						    font-size: 15px;
						  }
						  
						</style>';
		                echo '
		                <div class="wrap">
		                    <style>
		                        .woocommerce-help-tip{color:darkgray !important;}
		                        
		                    </style>
		                    <hr class="wp-header-end">';
		                self::hit_tab_content_fetch($tab);
		                switch ($tab) {
		                    case "abondend":
		                        echo '<div class="table-box table-box-main" id="available_offers_section" style="margin-top: 0px;border: 1px solid #ccc;border-top: unset !important;padding: 5px;">';
		                        	require_once(dirname(__FILE__)."/ui/abondend_cart.php");

		                        echo '</div>';
		                        break;
		                    case "Settings":
		                        echo '<div class="table-box table-box-main" id="available_offers_section" style="margin-top: 0px; border: 1px solid #ccc;border-top: unset !important;padding: 5px;">';
		                        	require_once(dirname(__FILE__)."/ui/connect_hitstacks.php");

		                        echo '</div>';
		                        break;
		                    
		                }
						echo '</div>';
		}
		public static function hit_tab_content_fetch($current = 'abondend') {
        $tabs = array(
                    'abondend' => __("Abondend Cart Tracking", 'hit-tech-market-product-add'),
                    'Settings' => __("Connect HitStacks", 'hit-tech-market-product-add')
                    
                );
                $html = '<h2 class="nav-tab-wrapper">';
                foreach ($tabs as $tab => $name) {
                    $class = ($tab == $current) ? 'nav-tab-active' : '';
                    $style = ($tab == $current) ? 'border-bottom: 1px solid transparent !important;' : '';
                    $html .= '<a style="text-decoration:none !important;' . $style . '" class="nav-tab ' . $class . '" href="?page=hitstacks&subtab=' . $tab . '">' . $name . '</a>';
                }
                $html .= '</h2>';
                echo $html;
            }	
	}
 }
 
new HITStacks_Class_Main();

add_action( 'woocommerce_add_to_cart', 'hit_customer_cart_tracking', 10 );
function hit_customer_cart_tracking() {
	if(is_user_logged_in()){
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	        return;
	    
	    $date = date('m-d-Y', time());
	    global $woocommerce;
	    $cart_values = array();
	    if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
		    foreach ( WC()->cart->get_cart() as $key => $value ) {
		    	$cart_values[] = array('p_id'=>$value['product_id'], 'v_id'=> $value['variation_id'],'qty' => $value['quantity'], 'date' => $date);
		    }
			update_user_meta(get_current_user_id(),'hit_cart_track',json_encode($cart_values));
		}
	}
}

add_action( 'woocommerce_thankyou', 'hit_remove_cart_values' );
function hit_remove_cart_values( $order_id ){
    if(is_user_logged_in()){
    	update_user_meta(get_current_user_id(),'hit_cart_track','');

	}
}
add_action( 'woocommerce_init', 'hit_get_cart_values' );
function hit_get_cart_values(){
	if( isset($_GET['hitcartkey'])) {
		global $wpdb;
		$connect_hitstacks = get_option('hit_connect_stacks');
		if(!empty($connect_hitstacks) && isset($connect_hitstacks['hit_enable_key_option']) && $connect_hitstacks['hit_enable_key_option'] == 'yes'){
			if(isset($connect_hitstacks['hit_key']) && $connect_hitstacks['hit_key'] != ''){
				$hit_key = $connect_hitstacks['hit_key'];
				ob_start();
				error_reporting(E_ERROR | E_PARSE);

				if($_GET['hitcartkey'] === $hit_key){

					$date = date('m-d-Y', time());
					$sb_cart_data = get_option('hit_stacks_ab_cart');
					
					if(!empty($sb_cart_data) && isset($sb_cart_data['hit_enable_ac_option']) && $sb_cart_data['hit_enable_ac_option'] == 'yes'){
						if(isset($sb_cart_data['hit_track_days']) && $sb_cart_data['hit_track_days'] != '' && $sb_cart_data['hit_track_days'] > 0){
							$cart_date = date('m-d-Y',strtotime("-".$sb_cart_data['hit_track_days']." days"));
							
							$sql = "SELECT u.user_email,u.display_name,u.ID,um.meta_value FROM ".$wpdb->prefix."users as u JOIN ".$wpdb->prefix."usermeta as um ON u.ID = um.user_id where um.meta_key='hit_cart_track' and um.meta_value RLIKE '".$cart_date."'";
							$results = $wpdb->get_results($sql);
							$cart_values = array();
							if(!empty($results)){
								foreach ( $results as $key => $value ) {
							    	$meta_data = json_decode($value->meta_value,true);
							    	
							    	$complete_meta = array();
							    	foreach ($meta_data as $single) {
							    		$product = wc_get_product( $single['p_id'] );
							    		$single['pname'] = $product->get_title();
							    		$single['plink'] = get_permalink( $single['p_id'] );
							    		$single['pimage'] = get_the_post_thumbnail_url( $single['p_id'] );
							    		$single['price'] = $product->get_price();
							    		$complete_meta[] = $single;
							    	}
							    	$value->meta_value = $complete_meta;
							    	$value->mobile =  ltrim(get_user_meta( $value->ID, 'billing_phone', true ), '0');;
							    	$value->country =  get_user_meta( $value->ID, 'billing_country', true );
							    	$cart_values[] = $value;
							    	
							    }
							}

						    echo json_encode($cart_values);
							die();
						}
					}
				}
			}
			
		}
	}
}