<?php


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
	<table class="table table-striped">
		<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">Catalog Name</th>
				<th scope="col">Details</th>
				<th scope="col"></th>
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
					<th scope="row"><?php echo $n ?></th>
					<td><?php echo $details->name ?></td>
					<td>
						<?php if($child_term) : ?>
							<a href="<?php echo home_url( '/my-account/' ); ?>product-subcatalogue?brand=<?php echo $details->term_id ?>">Details :<?php echo sizeof($child_term) ?></a>
						<?php else: ?>
							No Details
						<?php endif; ?>	
					</td>
					<td><a href="<?php echo home_url( '/my-account/' ); ?>product-group?brand=<?php echo $details->term_id ?>">View</a></td>
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
	<table class="table table-striped">
		<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">Catalog Name</th>
				<th scope="col">Details</th>
				<th scope="col"></th>
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
					<th scope="row"><?php echo $n ?></th>
					<td><?php echo $details->name ?></td>
					<td>
						<?php if($child_term) : ?>
							<a href="<?php echo home_url( '/my-account/' ); ?>product-subcatalogue?brand=<?php echo $details->term_id ?>">Details :<?php echo sizeof($child_term) ?></a>
						<?php else: ?>
							No Details
						<?php endif; ?>	
					</td>
					<td><a href="<?php echo home_url( '/my-account/' ); ?>product-group?brand=<?php echo $details->term_id ?>">View</a></td>
				</tr>
				<?php $n++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<?php
}
add_action( 'woocommerce_account_product-subcatalogue_endpoint', 'product_subcatalogue_endpoint_content' );
/*WooCommerce*/