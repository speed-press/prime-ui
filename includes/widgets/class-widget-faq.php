<?php
namespace SpeedPress\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FAQ_Accordion extends Widget_Base {

	public function get_name() {
		return 'spae-faq';
	}

	public function get_title() {
		return __( 'FAQ Accordion', 'speedpress-addons' );
	}

	public function get_icon() {
		return 'eicon-accordion';
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
			array( 'label' => __( 'Questions', 'speedpress-addons' ) )
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'q',
			array(
				'label'   => __( 'Question', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'What is included in monthly care?', 'speedpress-addons' ),
			)
		);
		$repeater->add_control(
			'a',
			array(
				'label'   => __( 'Answer', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Updates, backups, uptime checks, malware scans, and a written monthly note.', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'q' => __( 'Do you work on WooCommerce stores?', 'speedpress-addons' ),
						'a' => __( 'Yes. Checkout, product pages, and payment plugins are part of the review.', 'speedpress-addons' ),
					),
					array(
						'q' => __( 'Can you keep my current theme?', 'speedpress-addons' ),
						'a' => __( 'Yes. Speed work is done inside the theme you already use unless you ask for a rebuild.', 'speedpress-addons' ),
					),
				),
				'title_field' => '{{{ q }}}',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		echo '<div class="spae-faq">';
		foreach ( $items as $i => $item ) {
			$id = 'spae-faq-' . $this->get_id() . '-' . $i;
			echo '<div class="spae-faq__item">';
			echo '<button type="button" class="spae-faq__q" aria-expanded="false" aria-controls="' . esc_attr( $id ) . '">' . esc_html( $item['q'] ) . '</button>';
			echo '<div id="' . esc_attr( $id ) . '" class="spae-faq__a" hidden>' . wp_kses_post( wpautop( $item['a'] ) ) . '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}
