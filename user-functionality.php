<?php
/*************/
/*User issues*/
/*************/
/*Roles*/
$main = add_role(
    'main',
    __( 'Main Buyer', 'testdomain' ),
    array(
        'read'			=> true,
		'create_posts'	=> true,
        'edit_posts'	=> true
    )
);
$local = add_role(
    'local',
    __( 'Local Buyer', 'testdomain' ),
    array(
        'read'          => true,
        'create_posts'  => true,
        'edit_posts'    => true
    )
);
/*Login redirect*/
function admin_login_redirect( $redirect_to, $request, $user  ) {
    return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : home_url('/my-account/');
}
add_filter( 'login_redirect', 'admin_login_redirect', 10, 3 );
/*Limit Admin Bar*/
add_action( 'init', 'admin_bar' );
function admin_bar(){
    if ( current_user_can( 'administrator' ) ) {
        show_admin_bar( true );
    } else {
        show_admin_bar( false );
    }
}
/*Additional fields*/
function mos_additional_profile_fields( $user ) {
	$brand_name = get_the_author_meta( 'brand_name', $user->ID );
	$connected_buyer = get_the_author_meta( 'connected_buyer', $user->ID );
	$user_info = get_userdata($user->ID);
	// var_dump($user_info->roles[0]);
	?>
	<h3>Additional Information</h3>    
	<table class="form-table"> 
	<?php if ($user_info->roles[0] == 'main') : ?>
        <tr id="for-main-buyer">
            <th><label for="brand_name">Brand Name</label></th>
            <td>
                <input class="regular-text" id="brand_name" name="brand_name" type="text" value="<?php echo $brand_name ?>">
            </td>
        </tr>
    <?php elseif($user_info->roles[0] == 'local') : ?>
        <tr id="for-local-buyer">
            <th><label for="connected_buyer">Main Buyer List</label></th>
            <td>
                <input class="regular-text" id="connected_buyer" name="connected_buyer" type="text" value="<?php echo $connected_buyer ?>">                
            </td>
        </tr>
    <?php endif; ?>
    </table>
	<?php
}
add_action( 'show_user_profile', 'mos_additional_profile_fields' );
add_action( 'edit_user_profile', 'mos_additional_profile_fields' );
function mos_save_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    $error = 0;    
    if (!empty($_POST["brand_name"])) {
        $brand_name = sanitize_text_field($_POST["brand_name"]);
    } 
    if (!empty($_POST["connected_buyer"])) {
        $connected_buyer = sanitize_text_field($_POST["connected_buyer"]);
    }    
    if (!$error) {
        update_usermeta( $user_id, 'brand_name', $brand_name );
        update_usermeta( $user_id, 'connected_buyer', $connected_buyer );
    }
}
add_action( 'personal_options_update', 'mos_save_profile_fields' );
add_action( 'edit_user_profile_update', 'mos_save_profile_fields' );
/*************/
/*User issues*/
/*************/