<?php
/* AJAX action callback */
add_action( 'wp_ajax_view_details', 'view_details_ajax_callback' );
add_action( 'wp_ajax_nopriv_view_details', 'view_details_ajax_callback' );
/* Ajax Callback */
function view_details_ajax_callback () {
    $post_id = $_POST['product'];
    $output = array();
    $html = '';
    $design_no = get_post_meta( $design, '_onlinegreenotex_product_design_no', true );
    $details = get_post_meta( $post_id, '_onlinegreenotex_product_details_group', true );
    if ($details) {
    	$html .= '<table class="woocommerce-orders-table shop_table shop_table_responsive account-orders-table">';
    		$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<th>#</th>';
					$html .= '<th>Design NO</th>';
					$html .= '<th>Area Name</th>';
					$html .= '<th>Currency 1</th>';
					$html .= '<th>Unit Price 1</th>';
					$html .= '<th>Currency 2</th>';
					$html .= '<th>Unit Price 2</th>';
					$html .= '<th>Unit</th>';
					$html .= '<th>Lower Range</th>';
					$html .= '<th>High Range</th>';
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$n = 1;
		    	foreach ($details as $value) {
					$html .= '<tr>';
						$html .= '<td><strong>'.$n.'</strong></td>';
						$html .= '<td>'.$design_no.'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_area_name'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_currency_1'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_unit_price_1'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_currency_2'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_unit_price_2'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_unit'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_lower_range'].'</td>';
						$html .= '<td>'.$value['_onlinegreenotex_high_range'].'</td>';
					$html .= '</tr>';
					$n++;
		    	}
    		$html .= '</tbody>';
    	$html .= '</table>';
    }
	echo json_encode($html);
	//echo $html;
    exit; // required. to end AJAX request.
}