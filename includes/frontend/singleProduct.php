<?php

/**Add actions */

add_action( 'init', 'blazecheckout_button_hook' );

if (!function_exists("blazecheckout_button_hook")) {
	function blazecheckout_button_hook()
	{
		$placeButton = get_option('blaze_checkout_button_placement_option');
		$placementHook = array (
			'woocommerce_before_add_to_cart_quantity',
			'woocommerce_after_add_to_cart_quantity',
			'woocommerce_after_add_to_cart_button'
		);
	
		if($placeButton == 'other'){
			$placeButton = get_option('blaze_checkout_button_placement_option_other');
		}else if(($placeButton == '' || !in_array( $placeButton, $placementHook)) && $placeButton != 'disable'){
			$placeButton = 'woocommerce_before_add_to_cart_quantity';
		}
		if($placeButton != 'disable'){
			add_action($placeButton, "blazecheckout_single_add_payment_btn");
		}
	}
}


/**
 * Function for clearing old transients stored by our plugin.
 */
if (!function_exists("blazecheckout_single_add_payment_btn")) {
	function blazecheckout_single_add_payment_btn()
	{
		global $product;

		/**
		 * For external Type Product hide button
		 *
		 */
		$productType = ["external", "grouped"];

		if (!in_array($product->get_type(), $productType)) {
			echo '<div>
				<div id="blaze-product-sibling"></div>
			</div>';

		}
	}
}

?>