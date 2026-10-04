<?php
namespace SpeedPress\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Price_Table extends Widget_Base {

	public function get_name() {
		return 'spae-price-table';
	}

	public function get_title() {
		return __( 'Price Table', 'speedpress-addons' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return array( 'primeui' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			array( 'label' => __( 'Plan', 'speedpress-addons' ) )
		);

		$this->add_control(
			'plan',
			array(
				'label'   => __( 'Plan name', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Care plan', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => __( 'Price', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '$149',
			)
		);

		$this->add_control(
			'period',
			array(
				'label'   => __( 'Period', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '/ month', 'speedpress-addons' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'feature',
			array(
				'label'   => __( 'Feature', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Weekly updates', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'features',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'feature' => __( 'Core, plugin, and theme updates', 'speedpress-addons' ) ),
					array( 'feature' => __( 'Offsite backups', 'speedpress-addons' ) ),
					array( 'feature' => __( 'Uptime + malware checks', 'speedpress-addons' ) ),
					array( 'feature' => __( 'Monthly written report', 'speedpress-addons' ) ),
				),
				'title_field' => '{{{ feature }}}',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button text', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Start care plan', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label' => __( 'Button link', 'speedpress-addons' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'featured',
			array(
				'label'        => __( 'Highlight plan', 'speedpress-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$url  = isset( $s['button_link']['url'] ) ? $s['button_link']['url'] : '';
		$feat = ( 'yes' === $s['featured'] ) ? ' is-featured' : '';
		echo '<div class="spae-price' . esc_attr( $feat ) . '">';
		echo '<div class="spae-price__plan">' . esc_html( $s['plan'] ) . '</div>';
		echo '<div class="spae-price__amount">' . esc_html( $s['price'] ) . '<span>' . esc_html( $s['period'] ) . '</span></div>';
		echo '<ul class="spae-price__list">';
		if ( ! empty( $s['features'] ) && is_array( $s['features'] ) ) {
			foreach ( $s['features'] as $row ) {
				echo '<li>' . esc_html( $row['feature'] ) . '</li>';
			}
		}
		echo '</ul>';
		if ( $url && ! empty( $s['button_text'] ) ) {
			$tgt = ! empty( $s['button_link']['is_external'] ) ? ' target="_blank"' : '';
			echo '<a class="spae-price__btn" href="' . esc_url( $url ) . '"' . $tgt . '>' . esc_html( $s['button_text'] ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>';
	}
}
