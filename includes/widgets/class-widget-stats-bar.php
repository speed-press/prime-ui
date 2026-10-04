<?php
namespace SpeedPress\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Stats_Bar extends Widget_Base {

	public function get_name() {
		return 'spae-stats-bar';
	}

	public function get_title() {
		return __( 'Stats Bar', 'speedpress-addons' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_categories() {
		return array( 'primeui' );
	}

	public function get_script_depends() {
		return array( 'speedpress-addons' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			array( 'label' => __( 'Stats', 'speedpress-addons' ) )
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'number',
			array(
				'label'   => __( 'Number', 'speedpress-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 100,
			)
		);
		$repeater->add_control(
			'suffix',
			array(
				'label'   => __( 'Suffix', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '+',
			)
		);
		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'Label', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Sites maintained', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'stats',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'number' => 120,
						'suffix' => '+',
						'label'  => __( 'Sites cared for', 'speedpress-addons' ),
					),
					array(
						'number' => 40,
						'suffix' => '%',
						'label'  => __( 'Typical load drop', 'speedpress-addons' ),
					),
					array(
						'number' => 24,
						'suffix' => '/7',
						'label'  => __( 'Monitoring', 'speedpress-addons' ),
					),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style',
			array(
				'label' => __( 'Style', 'speedpress-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'num_color',
			array(
				'label'     => __( 'Number color', 'speedpress-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0F172A',
				'selectors' => array( '{{WRAPPER}} .spae-stat__num' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Label color', 'speedpress-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#475569',
				'selectors' => array( '{{WRAPPER}} .spae-stat__label' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$stats = isset( $s['stats'] ) && is_array( $s['stats'] ) ? $s['stats'] : array();
		echo '<div class="spae-stats">';
		foreach ( $stats as $stat ) {
			$num = isset( $stat['number'] ) ? (float) $stat['number'] : 0;
			echo '<div class="spae-stat">';
			echo '<div class="spae-stat__num"><span class="spae-count" data-target="' . esc_attr( $num ) . '">0</span>' . esc_html( $stat['suffix'] ) . '</div>';
			echo '<div class="spae-stat__label">' . esc_html( $stat['label'] ) . '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}
