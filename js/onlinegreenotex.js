jQuery(document).ready(function($) {
	$( "#dialog" ).dialog({

		resizable: false,        

    	height: "auto",
    	width: "auto",
		modal: true,
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		hide: {
			effect: "explode",
			duration: 1000
		}
	});

	$( ".view-product-variation" ).on( "click", function(e) {
		e.preventDefault();
		var product = $(this).data('product');
		// alert(product);
		$.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'view_details',
                'product' : product,
            },
            success: function(result){
                // console.log(result);
                $('#dialog .result').html(result);
                $( "#dialog" ).dialog( "open" );
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
		
	});	
});