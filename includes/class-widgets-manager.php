<?php
namespace SpeedPress\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Widgets_Manager {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'frontend_scripts' ) );
	}

	public static function handmade_widgets() {
		return array(
			'cta-card'      => array(
				'title'       => __( 'CTA Card', 'speedpress-addons' ),
				'description' => __( 'Headline, short copy, and a booking button.', 'speedpress-addons' ),
				'file'        => 'class-widget-cta-card.php',
				'class'       => __NAMESPACE__ . '\\Widgets\\CTA_Card',
				'group'       => 'marketing',
			),
			'feature-grid'  => array(
				'title'       => __( 'Feature Grid', 'speedpress-addons' ),
				'description' => __( 'Icon + title + text cards.', 'speedpress-addons' ),
				'file'        => 'class-widget-feature-grid.php',
				'class'       => __NAMESPACE__ . '\\Widgets\\Feature_Grid',
				'group'       => 'content',
			),
			'stats-bar'     => array(
				'title'       => __( 'Stats Bar', 'speedpress-addons' ),
				'description' => __( 'Animated number counters.', 'speedpress-addons' ),
				'file'        => 'class-widget-stats-bar.php',
				'class'       => __NAMESPACE__ . '\\Widgets\\Stats_Bar',
				'group'       => 'data',
			),
			'faq-accordion' => array(
				'title'       => __( 'FAQ Accordion', 'speedpress-addons' ),
				'description' => __( 'Accessible FAQ list.', 'speedpress-addons' ),
				'file'        => 'class-widget-faq.php',
				'class'       => __NAMESPACE__ . '\\Widgets\\FAQ_Accordion',
				'group'       => 'content',
			),
			'price-table'   => array(
				'title'       => __( 'Price Table', 'speedpress-addons' ),
				'description' => __( 'One pricing column.', 'speedpress-addons' ),
				'file'        => 'class-widget-price-table.php',
				'class'       => __NAMESPACE__ . '\\Widgets\\Price_Table',
				'group'       => 'marketing',
			),
			'testimonial'   => array(
				'title'       => __( 'Testimonial', 'speedpress-addons' ),
				'description' => __( 'Quote, name, role, stars.', 'speedpress-addons' ),
				'file'        => 'class-widget-testimonial.php',
				'class'       => __NAMESPACE__ . '\\Widgets\\Testimonial',
				'group'       => 'proof',
			),
		);
	}

	public static function available_widgets() {
		$list = self::handmade_widgets();
		foreach ( Catalog::all() as $id => $meta ) {
			$list[ $id ] = array(
				'title'       => $meta['title'],
				'description' => $meta['description'],
				'group'       => $meta['group'],
				'class'       => __NAMESPACE__ . '\\Widgets\\W_' . str_replace( '-', '_', $id ),
				'catalog'     => true,
			);
		}
		return $list;
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'primeui',
			array(
				'title' => __( 'PrimeUI', 'primeui' ),
				'icon'  => 'fa fa-bolt',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		$all     = self::available_widgets();
		$enabled = get_option( 'spae_enabled_widgets', array_keys( $all ) );
		if ( ! is_array( $enabled ) ) {
			$enabled = array_keys( $all );
		}

		foreach ( self::handmade_widgets() as $id => $meta ) {
			if ( ! in_array( $id, $enabled, true ) ) {
				continue;
			}
			$file = SPAE_PATH . 'includes/widgets/' . $meta['file'];
			if ( file_exists( $file ) ) {
				require_once $file;
				if ( class_exists( $meta['class'] ) ) {
					$widgets_manager->register( new $meta['class']() );
				}
			}
		}

		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}
		require_once SPAE_PATH . 'includes/class-catalog-widget.php';
		require_once SPAE_PATH . 'includes/generated-widget-classes.php';
		foreach ( Catalog::all() as $id => $meta ) {
			if ( ! in_array( $id, $enabled, true ) ) {
				continue;
			}
			$class = __NAMESPACE__ . '\\Widgets\\W_' . str_replace( '-', '_', $id );
			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}

	public function frontend_styles() {
		wp_enqueue_style( 'speedpress-addons', SPAE_URL . 'assets/css/frontend.css', array(), SPAE_VERSION );
		wp_enqueue_style( 'primeui-layouts', SPAE_URL . 'assets/css/layouts.css', array( 'speedpress-addons' ), SPAE_VERSION );
	}

	public function frontend_scripts() {
		wp_enqueue_script(
			'speedpress-addons',
			SPAE_URL . 'assets/js/frontend.js',
			array(),
			SPAE_VERSION,
			true
		);
	}
}
