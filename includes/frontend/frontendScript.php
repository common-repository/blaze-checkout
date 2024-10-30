<?php

$blazeCheckout = new blazeCheckout();


/**
 * load actions
 */
add_action("template_redirect", "blazecheckout_cart_json");
add_action("template_redirect", "blazecheckout_product_details");
add_action("template_redirect", "blazecheckout_cart_clear");
add_action( 'wp_enqueue_scripts', 'blazecheckout_window_script_load', -101 );
add_action("wp_enqueue_scripts", "blazecheckout_frontendScripts", -100);
add_action("wp_head", "blazecheckout_style_add");


/**
 * Register and enqueue frontend scripts.
 *
 * @return mixed
 */
if (!function_exists("blazecheckout_frontendScripts")) { 
	 function blazecheckout_frontendScripts()
    {
		$user = wp_get_current_user();
		$url = "https://cdn.prd.goblaze.co/blaze.js";
		$testMode = get_option("blaze_payment_setting_checkout_key");
		if ( $testMode == 1 && in_array( 'administrator', (array) $user->roles ) ) {
			wp_enqueue_script("blaze", $url , false);
		}else if( $testMode != 1 ){
			wp_enqueue_script("blaze", $url , false);
		}

    }
}


/**
 * Custom css add on head
 */
if (!function_exists("blazecheckout_style_add")) {
	function blazecheckout_style_add(){
		$blazeStyle = get_option("blaze_checkout_button_placement_option_css");
		if(!empty($blazeStyle)){
		?>
		<style>
			<?php
				echo esc_html($blazeStyle);
			?>
		</style>
		<?php
		}
	}
}

/**
 * 
 * Window script load
 */
if (!function_exists("blazecheckout_window_script_load")) {
	function blazecheckout_window_script_load(){
		if( is_admin() ) { 
			return true;
		}
		if ( is_product() ){

			global $product;

			/**
			 * For external Type Product hide button
			 *
			 */
			$product = wc_get_product();
			$productType = ["external", "grouped"];
			if (!in_array($product->get_type(), $productType)) {

				$jsonArray = blazecheckout_product_json_array($product);
				if ($jsonArray != '') {
			?>
					<script>
						window.wooSingleProductData = <?php echo $jsonArray; ?>;
					</script>
			<?php
				}
			}
		}
		
			global $woocommerce;
			if (empty ( $woocommerce->cart->get_cart() )) {  
				return false;
			} 
			$items = $woocommerce->cart->get_cart();
			
			$cartArray = blazecheckout_cart_json_array($items);
			
			if ($cartArray != '' ) {
		?>
				<script>
					window.woocommerceCartData = <?php echo $cartArray; ?>;
				</script>
		<?php
			}
	}
}
/**
 * Product array json
 */
if (!function_exists("blazecheckout_product_json_array")) {
	function blazecheckout_product_json_array($product)
	{
		$productArray = array();
		$productArray["product"]["id"] = $product->id;
		$productArray["product"]["title"] = $product->name;
		$productArray["product"]["image"]['src'] = blazecheckout_get_featured_img(
			$product->image_id
		);
		$i = 0;
		if ($product->get_type() == "variable") {
			foreach ($product->get_available_variations() as $vKey => $vValue) {
				# code...
				$productArray["product"]["variants"][$vKey]["id"] =
					$vValue["variation_id"];
				$productArray["product"]["variants"][$vKey]["price"] =
					$vValue["display_price"];
				$productArray["product"]["variants"][$vKey]["title"] = "";
			}
		} else {
			$productArray["product"]["variants"][$i]["id"] = $product->id;
			$productArray["product"]["variants"][$i]["price"] = $product->price;
			$productArray["product"]["variants"][$i]["title"] = $product->name;
		}
		if (count($product->attributes)) {
			foreach ($product->attributes as $key => $value) {
				# code...
				$productArray["product"]["attributes"][$key]["id"] = $value["id"];
				$productArray["product"]["attributes"][$key]["name"] = $value["name"];
				$productArray["product"]["attributes"][$key]["options"] =
					$value["options"];
				$productArray["product"]["attributes"][$key]["position"] =
					$value["position"];
				$productArray["product"]["attributes"][$key]["visible"] =
					$value["visible"];
				$productArray["product"]["attributes"][$key]["variation"] =
					$value["variation"];
			}
		} else {
			$productArray["product"]["attributes"] = [];
		}
		$jsonArray = json_encode($productArray);

		return $jsonArray;
	}
}

/**
 * Cart array Json details
 *  
 */
if (!function_exists("blazecheckout_cart_json_array")) {
	function blazecheckout_cart_json_array($items)
	{
		$cartArray = array();
		$i = 0;
		if (count($items) > 0) {
			foreach ($items as $item => $values) {
				$_product = wc_get_product($values["data"]->get_id());
				$cartArray["items"][$i]["id"] = $values["variation_id"];
				$cartArray["items"][$i]["variation_id"] = $values["variation_id"];
				$cartArray["items"][$i]["product_id"] = $values["product_id"];
				$cartArray["items"][$i]["name"] = $values["data"]->name;
				$cartArray["items"][$i]["price"] = (int) $values["data"]->price * 100;
				$cartArray["items"][$i]["quantity"] = $values["quantity"];
				$cartArray["items"][$i]["image"] = blazecheckout_get_featured_img(
					$values["data"]->image_id
				);
				$cartArray["items"][$i]["variant_title"] =
					$_product->get_type() == "variable" ? null : $values["data"]->name;
				$i++;
			}
		} else {
			$cartArray["items"] = array();
		}
		$jsonArray = json_encode($cartArray);
		return $jsonArray;
	}
}

/**
 * Cart page Json data
 *
 */

if (!function_exists("blazecheckout_cart_json")) {
	function blazecheckout_cart_json()
	{
		if (isset($_GET["is_cart_json"]) && $_GET["is_cart_json"] == "true") {
			global $woocommerce;
			$items = (array) $woocommerce->cart->get_cart();
			header("Content-Type:application/json");
			$cartArray = blazecheckout_cart_json_array($items);
			echo $cartArray;
			exit();
		} elseif (isset($_GET["is_json_full"]) && $_GET["is_json_full"] == "true" && is_cart()) {
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			header("Content-Type:application/json");
			echo json_encode($woocommerce->cart->get_cart());
			exit();
		}
	}
}

/**
 * Product page details json
 *
 */

if (!function_exists("blazecheckout_product_details")) {
	function blazecheckout_product_details()
	{
		if (isset($_GET["is_json"]) && $_GET["is_json"] == "true" && is_product()) {
			$product = wc_get_product();
			$id = $product->get_id();
			$productArray = blazecheckout_product_json_array($product);
			header("Content-Type:application/json");
			echo $productArray;
			exit();
		} elseif (isset($_GET["is_json_full"]) && $_GET["is_json_full"] == "true") {
			$product = (array) wc_get_product();
			header("Content-Type:application/json");
			echo str_replace('\u0000', "", json_encode($product));
			exit();
		}
	}
}
/**
 * getImage by image id
 */

if (!function_exists("blazecheckout_get_featured_img")) {
	function blazecheckout_get_featured_img($imgId)
	{
		$image = "";
		if ($imgId) {
			$imageArray = wp_get_attachment_image_src($imgId, "full");
			if (!empty($imageArray)) {
				$image = $imageArray[0];
			}
		}
		return $image;
	}
}

/**
 * Cart cache clear
 *
 */

if (!function_exists("blazecheckout_cart_clear")) {
	function blazecheckout_cart_clear()
	{
		if (isset($_GET["cart_clear"]) && $_GET["cart_clear"] == "true") {
			global $woocommerce;
			$woocommerce->cart->empty_cart();
		}
	}
}
?>