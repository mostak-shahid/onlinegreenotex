<?php
function onlinegreenotex_metaboxes() {
    $prefix = '_onlinegreenotex_';

    $product_details = new_cmb2_box(array(
        'id'           => $prefix . 'product_details',
        'title'        => 'Additional Details',
        'object_types' => array( 'product' ),
        //'show_on'      => array( 'key' => 'page-template', 'value' => 'page-template/link-gallery-page.php' ),
        'context'      => 'normal',
        'priority'     => 'default'
    )); 
    $product_details->add_field( array(
        'name' => 'Design NO',
        'id'   => $prefix . 'product_design_no',
        'type' => 'text',
    ));
    $product_details->add_field( array(
        'name' => 'Label Code',
        'id'   => $prefix . 'product_label_code',
        'type' => 'text',
    )); 
    $product_details->add_field( array(
        'name' => 'Label Name',
        'id'   => $prefix . 'product_label_name',
        'type' => 'text',
    )); 
    $product_details->add_field( array(
        'name' => 'Packing Qty',
        'id'   => $prefix . 'product_packing_qty',
        'type' => 'text',
    )); 
    $product_details->add_field( array(
        'name' => 'Packing Unit',
        'id'   => $prefix . 'product_packing_unit',
        'type' => 'text',
    ));
    $product_details->add_field( array(
        'name' => 'Details',
        'id'   => $prefix . 'product_details',
        'desc'    => 'Upload a PDF or enter an URL.',
        'type'    => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Add or Upload PDF File' // Change upload button text. Default: "Add or Upload File"
        ),
        /*'query_args' => array(
            'type' => 'application/pdf', // Make library only display PDFs.
            // Or only allow gif, jpg, or png images
            // 'type' => array(
            //  'image/gif',
            //  'image/jpeg',
            //  'image/png',
            // ),
        ),*/
    )); 

    $product_details_id = $product_details->add_field( array(
        'id'   => $prefix . 'product_details_group',
        'type' => 'group',
        'repeatable'  => true, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'       => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Entry', 'cmb2' ),
            'remove_button'     => __( 'Remove Entry', 'cmb2' ),
            'sortable'          => true,
            // 'closed'         => true, // true to have the groups closed by default
            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
        ),
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Area Name',
        'id'   => $prefix . 'area_name',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Currency 1',
        'id'   => $prefix . 'currency_1',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Unit Price 1',
        'id'   => $prefix . 'unit_price_1',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Currency 2',
        'id'   => $prefix . 'currency_2',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Unit Price 2',
        'id'   => $prefix . 'unit_price_2',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Unit Price 2',
        'id'   => $prefix . 'unit_price_2',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Unit',
        'id'   => $prefix . 'unit',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'Lower Range',
        'id'   => $prefix . 'lower_range',
        'type' => 'text',
    ));
    $product_details->add_group_field( $product_details_id, array(
        'name' => 'High Range',
        'id'   => $prefix . 'high_range',
        'type' => 'text',
    ));
}
add_action('cmb2_admin_init', 'onlinegreenotex_metaboxes');