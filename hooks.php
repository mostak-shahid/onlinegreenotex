<?php
function v_getUrl() {
  $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
  $url .= '://' . $_SERVER['SERVER_NAME'];
  $url .= in_array( $_SERVER['SERVER_PORT'], array('80', '443') ) ? '' : ':' . $_SERVER['SERVER_PORT'];
  $url .= $_SERVER['REQUEST_URI'];
  return $url;
}
function v_forcelogin() {
  if( !is_user_logged_in() ) {
    $url = v_getUrl();
    $whitelist = apply_filters('v_forcelogin_whitelist', array());
    $redirect_url = apply_filters('v_forcelogin_redirect', $url);
    if( preg_replace('/\?.*/', '', $url) != preg_replace('/\?.*/', '', wp_login_url()) && !in_array($url, $whitelist) ) {
      wp_safe_redirect( wp_login_url( $redirect_url ), 302 ); exit();
    }
  }
}
add_action('init', 'v_forcelogin');
function woocommerce_disable_shop_page() {
	$user = wp_get_current_user();
	if ( !in_array( 'administrator', (array) $user->roles ) ) {
	    global $post;
	    if (is_shop()):
	    global $wp_query;
	    $wp_query->set_404();
	    status_header(404);
	    endif;	    
	}
}
add_action( 'wp', 'woocommerce_disable_shop_page' );
/*WooCommerce*/
/**
 * Register new endpoint to use inside My Account page.
 *
 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
 */
function my_custom_endpoints() {
	add_rewrite_endpoint( 'product-catalogue', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'product-subcatalogue', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'product-group', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'product-details', EP_ROOT | EP_PAGES );
}

add_action( 'init', 'my_custom_endpoints' );

/**
 * Add new query var.
 *
 * @param array $vars
 * @return array
 */
function my_custom_query_vars( $vars ) {
	$vars[] = 'product-catalogue';
	$vars[] = 'product-subcatalogue';
	$vars[] = 'product-group';
	$vars[] = 'product-details';

	return $vars;
}

add_filter( 'query_vars', 'my_custom_query_vars', 0 );


/**
 * Insert the new endpoint into the My Account menu.
 *
 * @param array $items
 * @return array
 */
/*function my_custom_my_account_menu_items( $items ) {
	// Remove the logout menu item.
	$logout = $items['customer-logout'];
	unset( $items['customer-logout'] );

	// Insert your custom endpoint.
	$items['product-catalogue'] = __( 'Product Catalogue', 'woocommerce' );

	// Insert back the logout item.
	$items['customer-logout'] = $logout;

	return $items;
}*/

// add_filter( 'woocommerce_account_menu_items', 'my_custom_my_account_menu_items' );

/**
 * Custom help to add new items into an array after a selected item.
 *
 * @param array $items
 * @param array $new_items
 * @param string $after
 * @return array
 */
function my_custom_insert_after_helper( $items, $new_items, $after ) {
	// Search for the item position and +1 since is after the selected item key.
	$position = array_search( $after, array_keys( $items ) ) + 1;

	// Insert the new item.
	$array = array_slice( $items, 0, $position, true );
	$array += $new_items;
	$array += array_slice( $items, $position, count( $items ) - $position, true );

    return $array;
}

/**
 * Insert the new endpoint into the My Account menu.
 *
 * @param array $items
 * @return array
 */
function my_custom_my_account_menu_items( $items ) {
	$new_items = array();
	$new_items['product-catalogue'] = __( 'Product Catalogue', 'woocommerce' );

	// Add the new item after `orders`.
	// return my_custom_insert_after_helper( $items, $new_items, 'orders' );
	return my_custom_insert_after_helper( $items, $new_items, '' );
}

add_filter( 'woocommerce_account_menu_items', 'my_custom_my_account_menu_items' );






/**
 * Endpoint HTML content.
 */
function product_catalogue_endpoint_content() {
	// echo $_GET['data'];
	$brands = array();
	$user = wp_get_current_user();
	$role = $user->roles[0];
	$user_id = $user->ID;	
	if ($role == 'local'){
		$connected_buyer = get_user_meta( $user_id, 'connected_buyer', true );
		$brand_except = get_user_meta( $user_id, 'brand_except', true );
		$except_arr = explode(',', $brand_except);
		foreach ($except_arr as $value) {
			$except[] = str_replace(' ', '', $value);
		}
		if (@$connected_buyer){
			foreach ($connected_buyer as $buyer) {
				$all_brands[] = get_user_meta( $buyer, 'brand_name', true );
			}
			foreach ($all_brands as $value) {
				if ($value) {
					foreach($value as $val) {
						if (!in_array($val, $brands) AND !in_array($val, $except)){
							$brands[] = $val;
						}
					}
				}
			}

		}
	} else if($role = 'main'){
		$brands = get_user_meta( $user_id, 'brand_name', true );
	}
	// var_dump($brands);
	?>
	<table class="woocommerce-orders-table shop_table shop_table_responsive account-orders-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Catalog Name</th>
				<th>Details</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php if (@$brands) : ?>
			<?php $n = 1 ?>
			<?php foreach($brands as $brand) : ?>
				<?php 
				$details = get_term_by('id', $brand, 'product-brand'); 
				$child_term = get_term_children( $brand, 'product-brand' );
				?>
				<tr>
					<td data-title="#"><strong><?php echo $n ?></strong></td>
					<td data-title="Catalog Name"><?php echo $details->name ?></td>
					<td data-title="Details">
						<?php if($child_term) : ?>
							<a href="<?php echo home_url( '/my-account/' ); ?>product-subcatalogue?brand=<?php echo $details->term_id ?>">Details :<?php echo sizeof($child_term) ?></a>
						<?php else: ?>
							No Details
						<?php endif; ?>	
					</td>
					<td data-title="Action"><a class="button" href="<?php echo home_url( '/my-account/' ); ?>product-group?brand=<?php echo $details->term_id ?>">View</a></td>
				</tr>
				<?php $n++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<?php 
}

add_action( 'woocommerce_account_product-catalogue_endpoint', 'product_catalogue_endpoint_content' );

function product_subcatalogue_endpoint_content() {
	$brand = $_GET['brand'];
	$brands = get_term_children( $brand, 'product-brand' );
	?>
	<table class="woocommerce-orders-table shop_table shop_table_responsive account-orders-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Catalog Name</th>
				<th>Details</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php if (@$brands) : ?>
			<?php $n = 1 ?>
			<?php foreach($brands as $brand) : ?>
				<?php 
				$details = get_term_by('id', $brand, 'product-brand'); 
				$child_term = get_term_children( $brand, 'product-brand' );
				?>
				<tr>
					<td data-title="#"><strong><?php echo $n ?></strong></td>
					<td data-title="Catalog Name"><?php echo $details->name ?></td>
					<td data-title="Details">
						<?php if($child_term) : ?>
							<a href="<?php echo home_url( '/my-account/' ); ?>product-subcatalogue?brand=<?php echo $details->term_id ?>">Details :<?php echo sizeof($child_term) ?></a>
						<?php else: ?>
							No Details
						<?php endif; ?>	
					</td>
					<td data-title="Action"><a class="button" href="<?php echo home_url( '/my-account/' ); ?>product-group?brand=<?php echo $details->term_id ?>">View</a></td>
				</tr>
				<?php $n++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<?php
}
add_action( 'woocommerce_account_product-subcatalogue_endpoint', 'product_subcatalogue_endpoint_content' );

function product_group_endpoint_content() {
	$brand = $_GET['brand'];
	// echo $brand;
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'tax_query' => array(
	        array(
	            'taxonomy' => 'product-brand',
	            'field'    => 'id',
	            'terms'    => $brand,
	        ),
	    ),
	);
	// The Query
	$the_query = new WP_Query( $args ); ?>
	<!-- Modal -->
	<div id="dialog" title="Basic dialog">
		<div class="result"></div>
	</div>
	<table class="woocommerce-orders-table shop_table shop_table_responsive account-orders-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Design NO</th>
				<th>Label Code</th>
				<th>Label Name</th>
				<th>Packing Qty</th>
				<th>Packing Unit</th>
				<th>Specifications</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
	<?php if ( $the_query->have_posts() ) : ?>
		<?php $n = 1; ?>
	    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	    	<?php
	    	//_onlinegreenotex_product_design_no
	    	$design_no = get_post_meta( get_the_ID(), '_onlinegreenotex_product_design_no', true );
	    	$label_code = get_post_meta( get_the_ID(), '_onlinegreenotex_product_label_code', true );
	    	$label_name = get_post_meta( get_the_ID(), '_onlinegreenotex_product_label_name', true );
	    	$packing_qty = get_post_meta( get_the_ID(), '_onlinegreenotex_product_packing_qty', true );
	    	$packing_unit = get_post_meta( get_the_ID(), '_onlinegreenotex_product_packing_unit', true );
	    	$details = get_post_meta( get_the_ID(), '_onlinegreenotex_product_details', true );
	    	?>
	        <tr>
	        	<td data-title="#"><strong><?php echo $n ?></strong></td>
	        	<td data-title="Design NO">
	        		<a href="<?php echo home_url('/cart/') ?>?add-to-cart=<?php echo get_the_ID() ?>" data-quantity="1" class="" data-product_id="<?php echo get_the_ID() ?>"><i class="fa fa-shopping-cart"></i></a> <?php echo @$design_no ?>	        		
	        	</td>
	        	<td data-title="Label Code"><?php echo @$label_code ?></td>
	        	<td data-title="Label Name"><?php echo @$label_name ?></td>
	        	<td data-title="Packing Qty"><?php echo @$packing_qty ?></td>
	        	<td data-title="Packing Unit"><?php echo @$packing_unit ?></td>
	        	<td data-title="Specifications">
	        	<?php if ($details) : ?>
	        		<a href="<?php echo @$details ?>" target="_blank">View</a>
	        	<?php endif; ?>
	        	</td>
	        	<td data-title="Action">
	        		<a class="view-product-variation button" href="javascript:void(0)" data-toggle="modal" data-target="#productDetails" data-product="<?php echo get_the_ID() ?>">View</a>
	        	</td>
	        </tr>
	        <?php $n++; ?>
	    <?php endwhile; ?>
	<?php else : ?>
	    no posts found
	<?php endif; ?>
		</tbody>
	</table>
	<?php wp_reset_postdata();
}
add_action( 'woocommerce_account_product-group_endpoint', 'product_group_endpoint_content' );

add_filter( 'woocommerce_valid_order_statuses_for_cancel', 'filter_valid_order_statuses_for_cancel', 20, 2 );
function filter_valid_order_statuses_for_cancel( $statuses, $order = '' ){

    // Set HERE the order statuses where you want the cancel button to appear
    $custom_statuses    = array( 'pending', 'processing', 'on-hold', 'failed' );

    // Set HERE the delay (in days)
    $duration = 3; // 3 days

    // UPDATE: Get the order ID and the WC_Order object
    if( ! is_object( $order ) && isset($_GET['order_id']) )
        $order = wc_get_order( absint( $_GET['order_id'] ) );

    $delay = $duration*24*60*60; // (duration in seconds)
    $date_created_time  = strtotime($order->get_date_created()); // Creation date time stamp
    $date_modified_time = strtotime($order->get_date_modified()); // Modified date time stamp
    $now = strtotime("now"); // Now  time stamp

    // Using Creation date time stamp
    if ( ( $date_created_time + $delay ) >= $now ) return $custom_statuses;
    else return $statuses;
}
/*WooCommerce*/
/*Login*/
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(http://online.aiscript.net/wp-content/uploads/2020/01/cropped-logo.png);		
			width:auto;
			background-size: contain;
			background-repeat: no-repeat;
        	padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );