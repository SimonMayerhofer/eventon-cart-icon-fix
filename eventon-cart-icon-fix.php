<?php
/**
 * Plugin Name: EventON -  The7 Theme Cart Icon Fix
 * Description: This plugin fixes a problem with the cart icon in The7 theme where the cart icon and the cart items will not update after putting a EventON Ticket to the cart.
 * Version:     1.0.0
 * Author:      Tom Steinczhorn, Simon Mayerhofer
 * Author URI:  https://mayerhofer.it
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: eventon-cart-icon-fix
 * Domain Path: /languages
 *
 * EventON The7 Cart Icon Fix is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * EventON The7 Cart Icon Fix is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with EventON The7 Cart Icon Fix. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package eventon-cart-icon-fix
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'EventON_Cart_Icon_Fix' ) ) {
	/**
	 * Fix for the cart icon not updating after adding EventOn ticket to cart
	 */
	class EventON_Cart_Icon_Fix {
		const DOMAIN = 'eventon-cart-icon-fix';

		/**
		 * Initializes the plugin
		 *
		 * It registers the scripts and initializes hooks
		 */
		public static function init() {
			EventON_Cart_Icon_Fix::register_scripts();
			EventON_Cart_Icon_Fix::init_hooks();
		}

		/**
		 * Updates the mini cart in the header
		 */
		public static function update_mini_cart() {
			global $woocommerce;
			// Get the content of the cart with the actual template of the theme.
			ob_start();
			wc_get_template( 'cart/mini-cart.php' );
			$cart_content = ob_get_contents();
			ob_end_clean();

			// Fill the data array for the json call.
			$data = array(
				'cart_list' => $cart_content,
				'cart_count' => $woocommerce->cart->cart_contents_count,
				'cart_total' => $woocommerce->cart->get_cart_total(),
			);

			echo wp_json_encode( $data );
			wp_die(); // Important... Dont remove.
		}

		/**
		 * Register all scripts needed by the plugin
		 */
		public static function register_scripts() {
			wp_register_script(
				EventON_Cart_Icon_Fix::DOMAIN,
				plugins_url( 'eventon-cart-icon-fix.js', __FILE__ ),
				array( 'jquery' ),
				filemtime( plugin_dir_path( __FILE__ ) . '/eventon-cart-icon-fix.js' ),
				true
			);
			wp_enqueue_script( EventON_Cart_Icon_Fix::DOMAIN );
			wp_localize_script( EventON_Cart_Icon_Fix::DOMAIN, 'woocommerce_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}

		/**
		 * Initializes all hooks
		 */
		public static function init_hooks() {
			$self = new self();
			$update_mini_cart_function = array( $self, 'update_mini_cart' );

			// add action for ajax call.
			add_action( 'wp_ajax_eventon_cart_icon_fix_update_mini_cart', $update_mini_cart_function );
			add_action( 'wp_ajax_nopriv_eventon_cart_icon_fix_update_mini_cart', $update_mini_cart_function );
		}
	}

	EventON_Cart_Icon_Fix::init();
}
