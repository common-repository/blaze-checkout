<?php 

/**
 * Add Hook
 */
add_action("admin_menu", "blaze_settings");
add_action("admin_init", "blaze_payment_my_settings_init");

/***
 * Menu settings added
 *
 */

if (!function_exists("blaze_settings")) {
    function blaze_settings()
    {
        add_menu_page(
            __("Blaze Checkout Settings", "blazeCheckout"),
            __("Blaze Checkout", "blazeCheckout"),
            "manage_options",
            "blaze-checkout-settings",
            "blaze_menu_settings",
            "dashicons-tagcloud",
            6
        );
    }
}

/***
 * Blaze menu callback
 *
 */

if (!function_exists("blaze_menu_settings")) {
    function blaze_menu_settings()
    {
        echo '<h1 class="blaze-checkout">'.esc_html('Welcome to the Blaze Checkout Settings','blazeCheckout').'</h1>'; ?>
        <div class="blaze-notice">
            <div class="" >
                <div class="blaze-size notice notice-success" style="padding:15px;margin:0">
					<h4><?php echo esc_html('Welcome to Blaze Checkout!','blazeCheckout') ?></h4>
					<p><?php echo __('The first step to integrating Blaze Checkout with your WooCommerce store is to become a seller with Blaze. Contact to <a herf="mailto:woocommerce@bureau.id" >woocommerce@bureau.id </a>','blazeCheckout') ?></p>
                    <a href="https://dashboard.goblaze.co/" class="btn btn-primary button button-primary" target="_blank"><?php echo esc_html('Blaze Dashboard','blazeCheckout') ?></a> 
                </div>
            </div>
        </div>
        
			<div class="blaze-class-settings">
				<form method="POST" class="form-settings" action="options.php">
					<?php
                        settings_fields("blaze-payment-secure-settings");
                        do_settings_sections("blaze-payment-secure-settings");
                        submit_button();
                    ?>
				</form>
			</div>

    <?php
    }
}


/**
	 * Blaz Form Fileds
	 */
	if (!function_exists("blaze_payment_my_settings_init")) {
		function blaze_payment_my_settings_init()
		{
			add_settings_section(
				"blaze_payment_secure_settings",
				__("Settings", "blazeCheckout"),
				"my_setting_section_callback_function",
				"blaze-payment-secure-settings"
			);

			add_settings_field(
				"blaze_payment_setting_checkout_key",
				__("Blaze Test Mode", "blazeCheckout"),
				"blaze_payment_setting_checkout_key_markup",
				"blaze-payment-secure-settings",
				"blaze_payment_secure_settings"
			);

			add_settings_field( 
				'blaze_checkout_button_placement_option', 
				__("Select Product Button Location", "blazeCheckout"), 
				'blaze_checkout_button_placement_option_markup', 
				'blaze-payment-secure-settings', 
				'blaze_payment_secure_settings', 
				array('class' => 'blaze-button-placement')
			);

			
			add_settings_field( 
				'blaze_checkout_button_placement_option_other', 
				__("Other Button Placement hook", "blazeCheckout"), 
				'blaze_checkout_button_placement_option_other_markup', 
				'blaze-payment-secure-settings', 
				'blaze_payment_secure_settings', 
				array('class' => 'blaze-other-button')
			
			);

			
			add_settings_field( 
				'blaze_checkout_woo_checkout_option', 
				__("Checkout Page Button Placement", "blazeCheckout"), 
				'blaze_checkout_woo_checkout_option_markup', 
				'blaze-payment-secure-settings', 
				'blaze_payment_secure_settings'
			
			);
			
			
			add_settings_field( 
				'blaze_checkout_cart_option', 
				__("Cart Page Button Placement", "blazeCheckout"), 
				'blaze_checkout_cart_option_markup', 
				'blaze-payment-secure-settings', 
				'blaze_payment_secure_settings'
			
			);

			
			// add_settings_field( 
			// 	'blaze_checkout_button_placement_option_css', 
			// 	__("Button Placement Style", "blazeCheckout"), 
			// 	'blaze_checkout_button_placement_option_css_markup', 
			// 	'blaze-payment-secure-settings', 
			// 	'blaze_payment_secure_settings', 
			// );

			register_setting(
				"blaze-payment-secure-settings",
				"blaze_pyemnt_security_field"
			);
			register_setting(
				"blaze-payment-secure-settings",
				"blaze_payment_setting_checkout_key"
			);

			register_setting('blaze-payment-secure-settings','blaze_checkout_button_placement_option');
			register_setting('blaze-payment-secure-settings','blaze_checkout_button_placement_option_other');
			register_setting('blaze-payment-secure-settings','blaze_checkout_woo_checkout_option');
			register_setting('blaze-payment-secure-settings','blaze_checkout_cart_option');
			register_setting('blaze-payment-secure-settings','blaze_checkout_button_placement_option_css');

		}
	}
	
	/**
	 * Blaze Checkout Single Product options 
	 */
	function blaze_checkout_button_placement_option_markup() { 
		$buttonPlacement = get_option('blaze_checkout_button_placement_option');
		$messageDetails = __( 'Other , is available for users with advanced understanding of WordPress hooks. If Other is selected, a valid WordPress action hook must be entered in the next field, Enter Alternate Product Button Location..', 'blazeCheckout' );
		$selected = __( 'selected', 'blazeCheckout' );
		?>
		<select id="blaze_checkout_button_placement_option" name="blaze_checkout_button_placement_option">
			<option value="woocommerce_before_add_to_cart_quantity" <?php if ( $buttonPlacement == 'woocommerce_before_add_to_cart_quantity') { echo $selected ; }?> > <?php echo __('Before Quantity Selection','blazeCheckout'); ?> </option>
			<option value="woocommerce_after_add_to_cart_quantity" <?php if ( $buttonPlacement == 'woocommerce_after_add_to_cart_quantity') { echo $selected; } ?> > <?php echo __('After Quantity Selection' , 'blazeCheckout'); ?></option>
			<option value="woocommerce_after_add_to_cart_button" <?php if ( $buttonPlacement == 'woocommerce_after_add_to_cart_button') { echo $selected; } ?>  > <?php echo __('After Add to Cart Button' , 'blazeCheckout'); ?> </option>
			<option value="other" <?php if (  $buttonPlacement == 'other') { echo $selected; }?> > <?php echo __('Other' , 'blazeCheckout'); ?>  </option>
			<option value="disable" <?php if ( $buttonPlacement == 'disable') { echo $selected; }?> > <?php echo __('Disable' , 'blazeCheckout'); ?>  </option>
		</select>
		<p><?php echo esc_html( $messageDetails ); ?></p>
		<?php
	}

	/**
	 * Blaze Checkout Single Product hook Options
	 */
	function blaze_checkout_button_placement_option_other_markup() { 
		$buttonOther = get_option("blaze_checkout_button_placement_option_other");
		$messageDetails = __( 'Enter an alternative location for displaying the Blaze Checkout button is available for users with advanced understanding of WordPress hooks.', 'blazeCheckout' );
		?>
		<input type="text" name="blaze_checkout_button_placement_option_other" class="min-w-300" value="<?php echo esc_attr($buttonOther); ?>" id="blaze_checkout_button_placement_option_other" >
		<p><?php echo esc_html( $messageDetails ); ?></p>
		<?php
	}

	
	
	/**
	 * Blaze Checkout Woo checkout page 
	 */
	function blaze_checkout_woo_checkout_option_markup() { 
		$buttonCheckoutPlacement = get_option('blaze_checkout_woo_checkout_option');
		$selected = __( 'selected', 'blazeCheckout' );
		?>
		
		<select id="blaze_checkout_woo_checkout_option" name="blaze_checkout_woo_checkout_option">
			<option value="woocommerce_review_order_before_submit" <?php if (   $buttonCheckoutPlacement == 'woocommerce_review_order_before_submit') { echo $selected; } ?> > <?php echo __('Before Checkout Button','blazeCheckout'); ?> </option>
			<option value="woocommerce_review_order_after_submit" <?php if (  $buttonCheckoutPlacement == 'woocommerce_review_order_after_submit') { echo $selected; } ?> > <?php echo __('After Checkout Button' , 'blazeCheckout'); ?></option>
			<option value="woocommerce_before_checkout_form" <?php if ( $buttonCheckoutPlacement == 'woocommerce_before_checkout_form') { echo $selected; } ?> > <?php echo __('Before Checkout Form' , 'blazeCheckout'); ?> </option>
			<option value="disable" <?php if ($buttonCheckoutPlacement == 'disable') { echo $selected; } ?> > <?php echo __('Disable' , 'blazeCheckout'); ?>  </option>
		</select>
		<?php
	}


	/**
	 * Blaze Style Add
	 */
	
	 function blaze_checkout_button_placement_option_css_markup(){
		$buttonCss = get_option("blaze_checkout_button_placement_option_css");
		$messageDetails = __( 'Add Additional Css for Button Placement.', 'blazeCheckout' );
		?>
		<textarea name="blaze_checkout_button_placement_option_css" id="" cols="30" rows="10"><?php echo esc_attr($buttonCss); ?></textarea>
		<p><?php echo esc_html( $messageDetails ); ?></p>
		<?php
	 }
	
	/**
	 * Blaze Checkout Woo checkout page 
	 */
	function blaze_checkout_cart_option_markup() { 
		$buttonCartPlacement = get_option('blaze_checkout_cart_option');
		$selected = __( 'selected', 'blazeCheckout' );

		?>
		<select id="blaze_checkout_cart_option" name="blaze_checkout_cart_option">
			<option value="woocommerce_proceed_to_checkout" <?php  if ( $buttonCartPlacement == 'woocommerce_proceed_to_checkout') { echo $selected;  } ?> > <?php echo __('Before Cart Button','blazeCheckout'); ?> </option>
			<option value="woocommerce_after_cart_totals" <?php if ( $buttonCartPlacement == 'woocommerce_after_cart_totals') { echo $selected;  } ?> > <?php echo __('After Cart Button' , 'blazeCheckout'); ?></option>
			<option value="woocommerce_before_cart" <?php if ( $buttonCartPlacement == 'woocommerce_before_cart') { echo $selected;  } ?> > <?php echo __('Before Cart Form' , 'blazeCheckout'); ?> </option>
			<option value="disable"  <?php if ( $buttonCartPlacement == 'disable') { echo $selected;  } ?> > <?php echo __('Disable' , 'blazeCheckout'); ?>  </option>
		</select>
		<?php
	}

    /**
	 * Blaze Settings show
	 */

	if (!function_exists("my_setting_section_callback_function")) {
		function my_setting_section_callback_function()
		{
			// echo '<p>Intro text for our settings section</p>';
		}
	}

	/**
	 * Blaze Settings payment
	 */

	if (!function_exists("blaze_payment_setting_checkout_key_markup")) {
		function blaze_payment_setting_checkout_key_markup()
		{
            $options = get_option("blaze_payment_setting_checkout_key");
            $checked = '';
            if($options == 1){
                $checked = 'checked';
            }
			$messageDetails = __( 'When test mode is enabled, only logged-in admin users will see the Blaze Checkout button.', 'blazeCheckout' ); 
		?>
			<input type="checkbox" name="blaze_payment_setting_checkout_key" value="1" id="blaze_checkout_test_mode" <?php echo esc_attr($checked)?>>
            <label for="blaze_checkout_test_mode">Enable test mode</label>
            <p><?php echo esc_html( $messageDetails ); ?></p>
	<?php
		}
	}