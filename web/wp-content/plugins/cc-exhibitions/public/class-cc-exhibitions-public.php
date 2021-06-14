<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.neilarnold.com
 * @since      1.0.0
 *
 * @package    Cc_Exhibitions
 * @subpackage Cc_Exhibitions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cc_Exhibitions
 * @subpackage Cc_Exhibitions/public
 * @author     Neil P. Arnold <neilparnold@gmail.com>
 */
class Cc_Exhibitions_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cc-exhibitions-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cc-exhibitions-public.js', array( 'jquery' ), $this->version, false );
	}

	public function register_shortcodes() {
		add_shortcode( 'display_exhibitions', array( $this, 'shortcode_display_exhibitions' ) );
	}

	public function shortcode_display_exhibitions( $atts ) {
		$atts = shortcode_atts( [ 'type' => 'upcoming' ], $atts );

		$pagination  = '';
		$summary     = '';
		$search_args = Cc_Exhibitions_Admin::get_search_args();

		if ( 'travelling' === $atts['type'] ) {
			$search_args['is_travelling'] = 1;
		} else {
			$search_args['type'] = $atts['type'];
		}

		$posts = Cc_Exhibitions_Admin::get_all( $search_args, $pagination, $summary );

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/display_exhibitions.php';
		return ob_get_clean();
	}

}
