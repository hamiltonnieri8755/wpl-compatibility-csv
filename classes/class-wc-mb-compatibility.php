<?php
/**
 * Compatibility CSV Upload Meta Box
 *
 *
 * @author      Hamilton Nieri
 * @category    Developer
 * @package     
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_MetaBox_Compatibility
 */
class WC_MetaBox_Compatibility {

	/**
     * Class constructor
     *
     * @access public
     * @param
     */
    public function __construct() {
    	$this->wcmc_enqueue();
    }

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public function output() {
		include plugin_dir_path( __FILE__ ) . '../view/html-metabox-compatibility.php';
	}

	/**
     * Enqueue Style
     *
     * @access public
     * @param 
     */
	public function wcmc_enqueue() {
        wp_enqueue_style( 'wcmc-style-custom', plugins_url( 'css/wcmc_style.css', dirname(__FILE__) ) );
        wp_enqueue_script( 'wcmc-script-main', plugins_url( 'js/wcmc_script.js', dirname(__FILE__) ), array(), '1.0.0', true);
    }
}

