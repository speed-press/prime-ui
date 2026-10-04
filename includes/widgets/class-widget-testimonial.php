<?php
namespace SpeedPress\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Testimonial extends Widget_Base {

	public function get_name() {
		return 'spae-testimonial';
	}

	public function get_title() {
		return __( 'Testimonial', 'speedpress-addons' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return array( 'primeui' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			array( 'label' => __( 'Quote', 'speedpress-addons' ) )
		);

		$this->add_control(
			'quote',
			array(
				'label'   => __( 'Quote', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Checkout stopped stalling after the speed work. Support replies are short and useful.', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'name',
			array(
				'label'   => __( 'Name', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Store owner', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'role',
			array(
				'label'   => __( 'Role', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'WooCommerce shop', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'rating',
			array(
				'label'   => __( 'Stars', 'speedpress-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 0, 'max' => 5, 'step' => 1 ) ),
				'default' => array( 'size' => 5 ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label' => __( 'Photo', 'speedpress-addons' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$stars  = isset( $s['rating']['size'] ) ? (int) $s['rating']['size'] : 0;
		$img    = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
		echo '<figure class="spae-quote">';
		if ( $stars > 0 ) {
			echo '<div class="spae-quote__stars" aria-label="' . esc_attr( $stars . ' stars' ) . '">';
			for ( $i = 0; $i < $stars; $i++ ) {
				echo '<span>★</span>';
			}
			echo '</div>';
		}
		echo '<blockquote>' . esc_html( $s['quote'] ) . '</blockquote>';
		echo '<figcaption>';
		if ( $img ) {
			echo '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $s['name'] ) . '" />';
		}
		echo '<span><strong>' . esc_html( $s['name'] ) . '</strong><br />' . esc_html( $s['role'] ) . '</span>';
		echo '</figcaption>';
		echo '</figure>';
	}
}
