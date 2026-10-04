<?php
namespace SpeedPress\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CTA_Card extends Widget_Base {

	public function get_name() {
		return 'spae-cta-card';
	}

	public function get_title() {
		return __( 'CTA Card', 'primeui' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return array( 'primeui' );
	}

	public function get_keywords() {
		return array( 'cta', 'button', 'banner', 'primeui' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'layout', array( 'label' => __( 'Layout', 'primeui' ) ) );

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Sample layout', 'primeui' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'split',
				'options' => array(
					'split'    => __( '1. Split — text left, button right', 'primeui' ),
					'center'   => __( '2. Centered dark card', 'primeui' ),
					'gradient' => __( '3. Gradient banner', 'primeui' ),
					'overlay'  => __( '4. Image overlay', 'primeui' ),
					'outline'  => __( '5. Outline card', 'primeui' ),
					'stack'    => __( '6. Stacked eyebrow card', 'primeui' ),
					'inline'   => __( '7. Inline bar', 'primeui' ),
					'accent'   => __( '8. Side accent', 'primeui' ),
					'dual'     => __( '9. Two buttons', 'primeui' ),
					'media'    => __( '10. Image left, copy right', 'primeui' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Image', 'primeui' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'layout' => array( 'overlay', 'media' ) ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'primeui' ) ) );

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'primeui' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Free review', 'primeui' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'primeui' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Book a free site review', 'primeui' ) ) );
		$this->add_control( 'text', array( 'label' => __( 'Text', 'primeui' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'We check speed, security, and backups, then send a short action list.', 'primeui' ) ) );
		$this->add_control( 'button_text', array( 'label' => __( 'Button text', 'primeui' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Book now', 'primeui' ) ) );
		$this->add_control( 'button_link', array( 'label' => __( 'Button link', 'primeui' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$this->add_control( 'button_text_2', array( 'label' => __( 'Second button', 'primeui' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'See pricing', 'primeui' ), 'condition' => array( 'layout' => 'dual' ) ) );
		$this->add_control( 'button_link_2', array( 'label' => __( 'Second link', 'primeui' ), 'type' => Controls_Manager::URL, 'condition' => array( 'layout' => 'dual' ) ) );

		$this->add_control( 'icon', array( 'label' => __( 'Button icon', 'primeui' ), 'type' => Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ) ) );
		$this->add_control(
			'icon_pos',
			array(
				'label'   => __( 'Icon position', 'primeui' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => array(
					'left'  => array( 'title' => __( 'Left', 'primeui' ), 'icon' => 'eicon-h-align-left' ),
					'right' => array( 'title' => __( 'Right', 'primeui' ), 'icon' => 'eicon-h-align-right' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Style', 'primeui' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'bg', array( 'label' => __( 'Background', 'primeui' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pui-cta' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'color', array( 'label' => __( 'Text', 'primeui' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pui-cta' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'btn_bg', array( 'label' => __( 'Button', 'primeui' ), 'type' => Controls_Manager::COLOR, 'default' => '#2563EB', 'selectors' => array( '{{WRAPPER}} .pui-cta__btn' => 'background: {{VALUE}};' ) ) );
		$this->add_control( 'btn_color', array( 'label' => __( 'Button text', 'primeui' ), 'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => array( '{{WRAPPER}} .pui-cta__btn' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .pui-cta__title' ) );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'shadow', 'selector' => '{{WRAPPER}} .pui-cta' ) );
		$this->add_responsive_control( 'radius', array( 'label' => __( 'Radius', 'primeui' ), 'type' => Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'max' => 32 ) ), 'selectors' => array( '{{WRAPPER}} .pui-cta' => 'border-radius: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'pad', array( 'label' => __( 'Padding', 'primeui' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px' ), 'selectors' => array( '{{WRAPPER}} .pui-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	private function button( $s, $text_key = 'button_text', $link_key = 'button_link', $class = 'pui-cta__btn' ) {
		$text = isset( $s[ $text_key ] ) ? $s[ $text_key ] : '';
		$link = isset( $s[ $link_key ]['url'] ) ? $s[ $link_key ]['url'] : '';
		if ( ! $text || ! $link ) {
			return '';
		}
		$tgt  = ! empty( $s[ $link_key ]['is_external'] ) ? ' target="_blank"' : '';
		$icon = '';
		if ( ! empty( $s['icon']['value'] ) && 'button_text' === $text_key ) {
			ob_start();
			Icons_Manager::render_icon( $s['icon'], array( 'aria-hidden' => 'true' ) );
			$icon = ob_get_clean();
		}
		$inner = 'left' === ( $s['icon_pos'] ?? 'right' ) ? $icon . '<span>' . esc_html( $text ) . '</span>' : '<span>' . esc_html( $text ) . '</span>' . $icon;
		return '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $link ) . '"' . $tgt . '>' . $inner . '</a>';
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$lay  = isset( $s['layout'] ) ? $s['layout'] : 'split';
		$img  = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
		$copy = '';
		if ( ! empty( $s['eyebrow'] ) ) {
			$copy .= '<div class="pui-cta__eye">' . esc_html( $s['eyebrow'] ) . '</div>';
		}
		if ( ! empty( $s['title'] ) ) {
			$copy .= '<h3 class="pui-cta__title">' . esc_html( $s['title'] ) . '</h3>';
		}
		if ( ! empty( $s['text'] ) ) {
			$copy .= '<p class="pui-cta__text">' . esc_html( $s['text'] ) . '</p>';
		}
		$btn = $this->button( $s );
		$btn2 = 'dual' === $lay ? $this->button( $s, 'button_text_2', 'button_link_2', 'pui-cta__btn is-ghost' ) : '';
		echo '<div class="pui-cta is-' . esc_attr( $lay ) . '">';
		if ( in_array( $lay, array( 'overlay', 'media' ), true ) && $img ) {
			echo '<div class="pui-cta__media" style="background-image:url(' . esc_url( $img ) . ')"></div>';
		}
		echo '<div class="pui-cta__copy">' . $copy . '</div>'; // phpcs:ignore
		echo '<div class="pui-cta__actions">' . $btn . $btn2 . '</div>'; // phpcs:ignore
		echo '</div>';
	}
}
