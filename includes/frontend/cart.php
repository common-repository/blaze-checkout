<?php

/**Add actions */

add_action( 'init', 'blazecheckout_button_cart_checkout_hook' );

if (!function_exists("blazecheckout_button_cart_checkout_hook")) {
	function blazecheckout_button_cart_checkout_hook()
	{
		$placeButton = get_option('blaze_checkout_cart_option');
		$placementCartHook = array (
			'woocommerce_proceed_to_checkout',
			'woocommerce_after_cart_totals',
			'woocommerce_before_cart'
		);
	
		if(($placeButton == '' || !in_array( $placeButton, $placementCartHook) && $placeButton != 'disable' ) ){
			$placeButton = 'woocommerce_proceed_to_checkout';
		}
		if($placeButton != 'disable'){
			add_action($placeButton, "blazecheckout_add_cart_script");
		}

		$placeButtonCheckout = get_option('blaze_checkout_woo_checkout_option');
		$placementCheckoutHook = array (
			'woocommerce_review_order_before_submit',
			'woocommerce_review_order_after_submit',
			'woocommerce_before_checkout_form'
		);
	
		if( ($placeButtonCheckout == '' || !in_array( $placeButtonCheckout, $placementCheckoutHook) )  && $placeButtonCheckout != 'disable' ){
			$placeButtonCheckout = 'woocommerce_review_order_before_submit';
		}
		if($placeButtonCheckout != 'disable'){
			add_action($placeButtonCheckout, "blazecheckout_add_cart_script");
		}
	}
}


/**
 * Cart page Add button
 * 
 */

if (!function_exists("blazecheckout_add_cart_script")) {
function blazecheckout_add_cart_script(){
		global $woocommerce;
		$items = (array) $woocommerce->cart->get_cart();
		if(count($items) > 0){
			echo '<div class="blaze-cart">';
				echo '<div id="blaze-cart-sibling"></div>';
			echo '</div>';
		}
	}
}


?>