<?php

/**
 * 
 * Add action 
 */
add_action( 'woocommerce_admin_order_data_after_order_details',  "blazecheckout_order_details" );
add_filter( 'manage_edit-shop_order_columns',"blazecheckout_order_column", 20 );
add_filter( 'manage_edit-shop_order_columns',"blazecheckout_risk_order_column", 20 );
add_action( 'manage_shop_order_posts_custom_column' , "blazecheckout_orders_list_column_content" , 20, 2 );


/**
 * Customers Woocommerce order details 
 * 
 */
if (!function_exists("blazecheckout_order_details")) {
	function blazecheckout_order_details( $order ){  ?>
		<div class="form-field form-field-wide wc-customer-user">
			<?php 
				$order_id = $order->get_id();
				$blazeMode  = get_post_meta( $order_id, 'blaze_payment_mode', true );
				$blazecheckoutId = get_post_meta( $order_id, 'blaze_payment_id', true );
				if ($blazeMode === 'UPI') {
					echo '<h4>'.esc_html("Blaze Checkout","blazeCheckout").'</h4>';
					echo '<p><strong>'.esc_html("Blaze Checkout Id:","blazeCheckout").'</strong>' . esc_attr($blazecheckoutId , 'blazeCheckout') . '</p>'; 
				}else if ($blazeMode === 'COD') {
					echo '<h4>'.esc_html("Blaze Checkout",'blazeCheckout').'</h4>';
					echo '<p><strong>'.esc_html("Blaze Checkout Type:","blazeCheckout").'</strong>COD</p>'; 
				}
			?>
		</div>
	<?php 
	}
}
        
/***
 * Woocommerce custom shop order 
 * 
 */
if (!function_exists("blazecheckout_order_column")) {

	function blazecheckout_order_column($columns){
		$reordered_columns = array();
		// Inserting columns to a specific location
		foreach( $columns as $key => $column){
			$reordered_columns[$key] = $column;
			if( $key ==  'order_status' ){
				// Inserting after "Status" column
				$reordered_columns['blaze-column'] = __( 'Blaze Payment Id','blazeCheckout');
			}
		}
		return $reordered_columns;
	}
}  
/***
 * Woocommerce custom shop order 
 * 
 */
if (!function_exists("blazecheckout_risk_order_column")) {

	function blazecheckout_risk_order_column($columns){
		$reordered_columns = array();
		// Inserting columns to a specific location
		foreach( $columns as $key => $column){
			$reordered_columns[$key] = $column;
			if( $key == 'blaze-column' ){
				$reordered_columns['blaze-risk'] = __( 'Blaze Order Risk','blazeCheckout');
			}
		}
		return $reordered_columns;
	}
}

/**
 * Woocommerce Order Column Add
 * 
 */
function blazecheckout_orders_list_column_content( $column, $post_id ){
	switch ( $column )
	{
		case 'blaze-column' :
			// Get custom post meta data
			$blazeTag = get_post_meta( $post_id, 'blaze_tags', true );
			$blazeMode  = get_post_meta( $post_id, 'blaze_payment_mode', true );
			$blazecheckoutId = get_post_meta( $post_id, 'blaze_payment_id', true );
			if(!empty($blazeMode)){
				if($blazeMode === 'COD'){
					echo '-';
				}elseif ($blazeMode === 'UPI') {
					echo esc_attr($blazecheckoutId);
				}
			}else{
				echo '-';
			}
		break;
		case 'blaze-risk' :
			// Get custom post meta data
			$blazeRisk = get_post_meta( $post_id, 'blaze_risk', true );
			if(!empty($blazeRisk)){
				if($blazeRisk == 'GREEN'){
					echo '<div class="blaze-green"><span class=" fs-24 font-weight-600 dashicons dashicons-saved blaze-text-green"></span></a>';
				}elseif ($blazeRisk == 'RED') {
					echo '<div class="blaze-red"><span class=" fs-24 font-weight-600 dashicons dashicons-no-alt blaze-text-red"></span></a>';
				}
			}else{
				echo '-';
			}
		break;

	}
}

?>