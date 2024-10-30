<?php 

/**Woocomerece Order And customer Hook */
add_action('show_user_profile', 'blazecheckout_user_profile_fields');

// Hooks near the bottom of the profile page (if not current user) 
add_action('edit_user_profile', 'blazecheckout_user_profile_fields');

/**
 * Customer Custom filed data added
 */

if (!function_exists("blazecheckout_user_profile_fields")) {
    function blazecheckout_user_profile_fields($user){
    ?>
        <h3>Customer Blaze info</h>
        <table class="form-table">
            <tr>
                <th>
                    <label for="blaze_phone"><?php _e( 'Phone Number','blazeCheckout' ); ?></label>
                </th>
                <td>
                    <input type="number" name="" disabled="disabled" readonly id="blaze_phone" placeholder="<?php echo esc_attr( get_the_author_meta( 'blaze_phone', $user->ID ),'blazeCheckout' ); ?>" class="regular-text" /> <span class="description "> <?php _e( 'Phone Number cannot be changed','blazeCheckout' ); ?> .</span>
                </td>
            </tr>
        </table>
    <?php 
    }
}

?>