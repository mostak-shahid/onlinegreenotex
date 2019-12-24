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
	$brand_except = get_the_author_meta( 'brand_except', $user->ID );
	$user_info = get_userdata($user->ID);
	// var_dump($user_info->roles[0]);
	?>
	<h3>Additional Information</h3>    
	<table class="form-table"> 
	<?php if ($user_info->roles[0] == 'main') : ?>
        <?php 
        $product_brands_all = mos_get_terms ('product-brand');
        if (@$product_brands_all) {
            $n = 0;
            foreach($product_brands_all as $value){
                if ($value["parent"] == 0){
                    $product_brands[$n]["term_id"] = $value["term_id"];
                    $product_brands[$n]["name"] = $value["name"];
                    $n++;
                }
            }
        }
/*
array(7) {
  [0]=&gt;
  array(8) {
    ["term_id"]=&gt;
    string(2) "32"
    ["taxonomy"]=&gt;
    string(13) "product-brand"
    ["name"]=&gt;
    string(10) "Tag Studio"
    ["slug"]=&gt;
    string(10) "tag-studio"
    ["description"]=&gt;
    string(574) "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
    ["parent"]=&gt;
    string(1) "0"
    ["count"]=&gt;
    string(1) "0"
    ["term_group"]=&gt;
    string(1) "0"
  }
}
        
*/
        ?>
        <tr id="for-main-buyer">
        <?php // var_dump($brand_name); ?>
            <th><label for="brand_name">Brand Name</label></th>
            <td>
                <!-- <input class="regular-text" id="brand_name" name="brand_name" type="text" value="<?php //echo $brand_name ?>"> -->
                <?php if(@$product_brands) : ?>
                    <?php foreach ($product_brands as $product_brand) : ?>
                        <label for="brand_name_<?php echo $product_brand["term_id"] ?>"><input name="brand_name[]" type="checkbox" id="brand_name_<?php echo $product_brand["term_id"] ?>" value="<?php echo $product_brand["term_id"] ?>" <?php if($brand_name AND in_array($product_brand["term_id"], $brand_name)) echo 'checked' ?>><?php echo $product_brand["name"] ?></label>
                    <?php endforeach; ?>
                <?php endif ?>
            </td>
        </tr>
    <?php elseif($user_info->roles[0] == 'local') : ?>
        <?php 
        $args = array(
            'role__in' => array('main'),
        );
        $raw_users = get_users( $args );
        if (@$raw_users) {
            $n = 0;
            foreach ($raw_users as $raw_user) {
                $all_users[$n]["ID"] = $raw_user->data->ID;
                $all_users[$n]["display_name"] = $raw_user->data->display_name;
                $n++;
            }
        }
        /*
        array(2) {
  [0]=>
  object(WP_User)#1934 (8) {
    ["data"]=>
    object(stdClass)#1932 (10) {
      ["ID"]=>
      string(1) "2"
      ["user_login"]=>
      string(10) "mainbuyer1"
      ["user_pass"]=>
      string(34) "$P$B9qaQzfqLfVXPkKa8ORwK1rLahx2H11"
      ["user_nicename"]=>
      string(10) "mainbuyer1"
      ["user_email"]=>
      string(20) "mainbuyer1@email.com"
      ["user_url"]=>
      string(0) ""
      ["user_registered"]=>
      string(19) "2019-12-23 06:17:19"
      ["user_activation_key"]=>
      string(0) ""
      ["user_status"]=>
      string(1) "0"
      ["display_name"]=>
      string(14) "Main Buyer One"
    }
    ["ID"]=>
    int(2)
    ["caps"]=>
    array(1) {
      ["main"]=>
      bool(true)
    }
    ["cap_key"]=>
    string(15) "wp_capabilities"
    ["roles"]=>
    array(1) {
      [0]=>
      string(4) "main"
    }
    ["allcaps"]=>
    array(4) {
      ["read"]=>
      bool(true)
      ["create_posts"]=>
      bool(true)
      ["edit_posts"]=>
      bool(true)
      ["main"]=>
      bool(true)
    }
    ["filter"]=>
    NULL
    ["site_id":"WP_User":private]=>
    int(1)
  }
}
        
        */
        ?>
        <tr id="for-local-buyer">
            <th><label for="connected_buyer">Main Buyer List</label></th>
            <td>
            <?php if (@$all_users) : ?>
                <?php foreach ($all_users as $user) : ?>
                    <!-- <input class="regular-text" id="connected_buyer" name="connected_buyer" type="text" value="<?php echo $connected_buyer ?>">   -->
                    <label for="connected_buyer_<?php echo $user["ID"] ?>"><input name="connected_buyer[]" type="checkbox" id="connected_buyer_<?php echo $user["ID"] ?>" value="<?php echo $user["ID"] ?>" <?php if(@$connected_buyer AND in_array($user["ID"], $connected_buyer)) echo 'checked' ?>><?php echo $user["display_name"] ?></label>
                <?php endforeach; ?>
            <?php endif; ?>              
            </td>
        </tr>
        <tr id="for-local-buyer-brand-except">
            <th><label for="brand_except">Brand Except</label></th>
            <td>
                <input class="regular-text" id="brand_except" name="brand_except" type="text" value="<?php echo $brand_except ?>">
                <p class="description">Please seperate the brand IDs with ',' Like 52,53.</p>
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
        $brand_name = $_POST["brand_name"];
    } 
    if (!empty($_POST["connected_buyer"])) {
        $connected_buyer = $_POST["connected_buyer"];
    }
    if (!empty($_POST["brand_except"])) {
        $brand_except = sanitize_text_field($_POST["brand_except"]);
    }    
    if (!$error) {
        update_usermeta( $user_id, 'brand_name', $brand_name );
        update_usermeta( $user_id, 'connected_buyer', $connected_buyer );
        update_usermeta( $user_id, 'brand_except', $brand_except );
    }
}
add_action( 'personal_options_update', 'mos_save_profile_fields' );
add_action( 'edit_user_profile_update', 'mos_save_profile_fields' );
/*************/
/*User issues*/
/*************/