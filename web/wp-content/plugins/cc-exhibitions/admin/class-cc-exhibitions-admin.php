<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.neilarnold.com
 * @since      1.0.0
 *
 * @package    Cc_Exhibitions
 * @subpackage Cc_Exhibitions/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cc_Exhibitions
 * @subpackage Cc_Exhibitions/admin
 * @author     Neil P. Arnold <neilparnold@gmail.com>
 */
class Cc_Exhibitions_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cc-exhibitions-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cc-exhibitions-admin.js', array( 'jquery' ), $this->version, false );
	}

	// Register Custom Post Type
	public function init_cpt() {

		$labels = array(
			'name'                  => _x( 'Exhibitions', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Exhibition', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Exhibitions', 'text_domain' ),
			'name_admin_bar'        => __( 'Exhibitions', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
			'attributes'            => __( 'Item Attributes', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Exhibitions', 'text_domain' ),
			'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Exhibition', 'text_domain' ),
			'edit_item'             => __( 'Edit Exhibition', 'text_domain' ),
			'update_item'           => __( 'Update Exhibition', 'text_domain' ),
			'view_item'             => __( 'View Exhibition', 'text_domain' ),
			'view_items'            => __( 'View Exhibitions', 'text_domain' ),
			'search_items'          => __( 'Search Item', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                  => 'exhibition',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Exhibition', 'text_domain' ),
			'description'           => __( 'Colby College Exhibitions', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-art',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);
		register_post_type( 'cc_exhibition', $args );

	}

	/**
	 * Get a list of all items.
	 *
	 * @param array  $search_args Search arguments from query string.
	 * @param string $pagination HTML for Pagination.
	 * @param string $summary HTML for Summary.
	 * @return array Post objects.
	 */
	public static function get_all( $search_args, &$pagination = null, &$summary = null ) {
		$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$posts_per_page = $search_args['posts_per_page'] ?? 10;
		$meta_query     = [];
		$tax_query      = [];
		$args           = [
			'post_type'      => array( 'cc_exhibition' ),
			'post_status'    => array( 'publish' ),
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'orderby'        => [
				'post_title' => 'ASC',
			],
		];

		if ( true === array_key_exists( 'keywords', $search_args ) ) {
			$args['s'] = $search_args['keywords'];
		}

		if ( true === array_key_exists( 'is_travelling', $search_args ) ) {
			$meta_query[] = [
				'key'     => 'e_is_travelling',
				'value'   => $search_args['sponsored'],
				'compare' => '=',
			];
		}

		if ( 'upcoming' === array_key_exists( 'type', $search_args ) ) {
			$meta_query[] = [
				// 'relation' => 'OR',
				// 'start_clause' => array(
				// 	'key'     => 'e_start_group_date',
				// 	'compare' => '>=',
				// 	'value'   => $today,
				// ),
				'end_clause' => array(
					'key'     => 'e_end_group_date',
					'compare' => '>=',
					'value'   => $today,
				),
			];
		}
		if ( 'current' === array_key_exists( 'type', $search_args ) ) {
			$meta_query[] = [
				'relation' => 'OR',
				'start_clause' => array(
					'key'     => 'e_start_group_date',
					'compare' => '>=',
					'value'   => $today,
				),
				'end_clause' => array(
					'key'     => 'e_end_group_date',
					'compare' => '<=',
					'value'   => $today,
				),
			];
		}
		if ( 'past' === array_key_exists( 'type', $search_args ) ) {
			$meta_query[] = [
				// 'relation' => 'OR',
				// 'start_clause' => array(
				// 	'key'     => 'e_start_group_date',
				// 	'compare' => '<=',
				// 	'value'   => $today,
				// ),
				'end_clause' => array(
					'key'     => 'e_end_group_date',
					'compare' => '<=',
					'value'   => $today,
				),
			];
		}

		$args['meta_query'] = $meta_query;
		// $args['tax_query']  = $tax_query;

		// Wb_Core_Admin::echo( $args );
		$query = new WP_Query( $args );
		$posts = $query->posts;
		if ( null !== $pagination ) {
			$pagination = self::get_pagination( $query );
		}

		$start = ( ( $paged - 1 ) * $posts_per_page ) + 1;
		$end   = $start + $query->post_count - 1;
		$total = $query->found_posts;

		if ( $total > 0 ) {
			$summary = "Showing <b>{$start}-{$end}</b> of <b>{$total}</b> results";
		} else {
			$summary = ''; // No results found with your search criteria.
		}

		return $posts;
	}

	/**
	 * Get pagination HTML from the search/get_all function.
	 *
	 * @param  array $query Query object from WP_Query.
	 * @return string HTML for the pagination element.
	 */
	public static function get_pagination( $query, $total = null ) {
		if ( ! isset( $total ) ) {
			$total = $query->max_num_pages;
		}
		return paginate_links( array(
			'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'total'        => $total,
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'format'       => '?paged=%#%',
			'show_all'     => false,
			'type'         => 'list',
			'end_size'     => 2,
			'mid_size'     => 1,
			'prev_next'    => true,
			'prev_text'    => sprintf( '<i></i> %1$s', __( '<i class="fa fa-angle-left" aria-hidden="true"></i> PREV', 'text-domain' ) ),
			'next_text'    => sprintf( '%1$s <i></i>', __( 'NEXT <i class="fa fa-angle-right" aria-hidden="true"></i>', 'text-domain' ) ),
			'add_args'     => false,
			'add_fragment' => '',
		) );

	}

	/**
	 * Get search args from the query string.
	 *
	 * @param  boolean $use_get True = GET, False/Null = WP get_query_var.
	 * @return Array   An array of query vars,
	 */
	public static function get_search_args( $use_get = false ) {
		$args = [];
		$vars = [
			'keywords',
			'category',
		];

		foreach ( $vars as $var ) {
			if ( ! $use_get ) {
				$value = get_query_var( $var );
			} else {
				$value = filter_input( INPUT_GET, $var, FILTER_SANITIZE_STRING );
			}
			if ( ! empty( $value ) ) {
				$args[ $var ] = $value;
			}
		}

		return $args;
	}

}
