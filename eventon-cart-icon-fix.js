jQuery(function($) {
	$( ".evoAddToCart" ).click(function() {  // Hook into the AddToCart Button event and trigger the UpdateMiniCart function
	  	// Ajax call to mode_theme_update_mini_cart function in function.php
		setTimeout(function(){  //Set a delay of 2 second to update the cart first
			$.ajax({
				url: woocommerce_params.ajax_url,
				data: {
					'action':'eventon_cart_icon_fix_update_mini_cart'
				},
				async: false,
				dataType: 'JSON',
				success:function(data) {
					//console.log(data);
					var cart = $('.wc-ico-cart');
					cart.find('.counter').text(data.cart_count);      //Update counter
					//cart.find('.woocommerce-Price-amount').remove();  //Remove old Total
					//cart.prepend( data.cart_total );                  //Insert new Total
					$('.shopping-cart-inner').html(data.cart_list);   //Replace the content of the cart with updated list
				},
				error: function(errorThrown){
					console.log("error: " + errorThrown);
				}
			});
		},2000); // delay in ms, can be ajusted if necessary
	});
});