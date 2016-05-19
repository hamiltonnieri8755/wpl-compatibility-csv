<?php
/**
 * Plugin Name: WPLAB Compatibility CSV
 * Plugin URI: https://www.wplab.com/
 * Description: An e-commerce toolkit that helps you upload compatibility csv
 * Version: 1.0
 * Author: Hamilton Nieri
 * Author URI: https://www.wplab.com/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * ----------------------------------------------------------------------
 * Copyright (C) 2016  Hamilton Nieri  (Email: hamiltonnieri8755@yahoo.com)
 * ----------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------
 */

// Including WP core file
if ( ! function_exists( 'get_plugins' ) )
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Including base class
if ( ! class_exists( 'WC_MetaBox_Compatibility' ) )
    require_once plugin_dir_path( __FILE__ ) . 'classes/class-wc-mb-compatibility.php';

// Whether plugin active or not
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) :

	/**
	 * Display Compatibility CSV Uploader Metabox on edit product page
	 **/

	add_action( 'add_meta_boxes', 'wpl_compatibility_meta_boxes' );

	function wpl_compatibility_meta_boxes() {

	    add_meta_box(
	        'wpl-compatibility',
	        'WPL Compatibility CSV Uploader',
	        'compatibility_csv_meta',
	        'product',
	        'side',
	        'default'
	    );

	}

	$wcmc = "";

	/**
	 * Outputs the content of the meta box "WPL Compatibility CSV Uploader"
	 */
	function compatibility_csv_meta( $post ) {
		
		global $wcmc;
		$wcmc = new WC_MetaBox_Compatibility( $post );
		echo $wcmc->output();

	}

	/**
	 * Save Compatibility Table ( CSV FORMAT ) To Post Meta
	 */	

	add_action( 'save_post', 'wpl_compatibility_csv_upload', 100, 3 );

	function wpl_compatibility_csv_upload( $post_id, $post, $update ) {

		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/csv');
		
		if ( $_POST['compatibility_csv_flag'] == '1' ) {
			
			// If users click on "Add Compatibility CSV" button after select csv file
			$uploadedfile = $_FILES['compatibility_csv'];

			// Validate Uploaded File Format
			if ( ! in_array( $uploadedfile['type'], $mimes )  ) {
				die($uploadedfile['type']);
			}

			$upload_overrides = array( 'test_form' => false );
			
			// Download File			
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

			if ( ! $movefile || isset($movefile['error']) ) {
				die( $movefile['error'] );
			}

			// - Parse csv file -
		
			$file = fopen($movefile['file'],"r");

			// Compatibility Header
			$compatibility_names = fgetcsv( $file );
			
			while ( ! feof($file) ) {
				
				$row = fgetcsv( $file );

				$compatible_app               = new stdClass();
				$compatible_app->notes        = '';
				$compatible_app->applications = array();

				// skip empty rows
				if ( empty( $row[0] ) ) continue;

				// each column
				foreach ($row as $col_index => $value) {
					$value = stripslashes( $value );
					$name  = $compatibility_names[ $col_index ];
					if ( $name == 'Notes' ) {
						$compatible_app->notes = $value;
					} else {

						$property = new stdClass();
						$property->name  = $name;
						$property->value = $value;

						$compatible_app->applications[ $name ] = $property;
					}

				}

				// add to array
				$compatible_applications[] = $compatible_app;
			}

			// remove Notes column
			$notes_index = array_search('Notes', $compatibility_names);
			unset( $compatibility_names[$notes_index] );

			update_post_meta( $post_id, '_ebay_item_compatibility_list', $compatible_applications );
			update_post_meta( $post_id, '_ebay_item_compatibility_names', $compatibility_names );
		}

	}

endif;