<?php
namespace SpeedPress\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Feature_Grid extends Widget_Base {

	public function get_name() {
		return 'spae-feature-grid';
	}

	public function get_title() {
		return __( 'Feature Grid', 'speedpress-addons' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'primeui' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			array( 'label' => __( 'Items', 'speedpress-addons' ) )
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'speedpress-addons' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .spae-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon text', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '01',
			)
		);
		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Faster pages', 'speedpress-addons' ),
			)
		);
		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'Text', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Caching, image weight, and database cleanup that stay within your theme.', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Cards', 'speedpress-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'icon'  => '01',
						'title' => __( 'Speed', 'speedpress-addons' ),
						'text'  => __( 'Core Web Vitals, caching, and lean assets.', 'speedpress-addons' ),
					),
					array(
						'icon'  => '02',
						'title' => __( 'Security', 'speedpress-addons' ),
						'text'  => __( 'Updates, firewall rules, and login hardening.', 'speedpress-addons' ),
					),
					array(
						'icon'  => '03',
						'title' => __( 'Backups', 'speedpress-addons' ),
						'text'  => __( 'Offsite copies you can restore without guessing.', 'speedpress-addons' ),
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style',
			array(
				'label' => __( 'Cards', 'speedpress-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'Background', 'speedpress-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F8FAFC',
				'selectors' => array( '{{WRAPPER}} .spae-card' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_color',
			array(
				'label'     => __( 'Text', 'speedpress-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0F172A',
				'selectors' => array( '{{WRAPPER}} .spae-card' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'     => __( 'Gap', 'speedpress-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 8, 'max' => 48 ) ),
				'default'   => array( 'size' => 20 ),
				'selectors' => array( '{{WRAPPER}} .spae-grid' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		echo '<div class="spae-grid">';
		foreach ( $items as $item ) {
			echo '<article class="spae-card">';
			if ( ! empty( $item['icon'] ) ) {
				echo '<div class="spae-card__icon">' . esc_html( $item['icon'] ) . '</div>';
			}
			if ( ! empty( $item['title'] ) ) {
				echo '<h3 class="spae-card__title">' . esc_html( $item['title'] ) . '</h3>';
			}
			if ( ! empty( $item['text'] ) ) {
				echo '<p class="spae-card__text">' . esc_html( $item['text'] ) . '</p>';
			}
			echo '</article>';
		}
		echo '</div>';
	}
}
