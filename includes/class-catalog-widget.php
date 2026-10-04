<?php
namespace SpeedPress\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
	return;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;

abstract class Catalog_Widget extends Widget_Base {

	abstract protected function catalog_id();

	protected function meta() {
		$item = Catalog::get( $this->catalog_id() );
		return $item ? $item : array(
			'title' => 'SpeedPress',
			'icon'  => 'eicon-shortcode',
			'type'  => 'text',
			'group' => 'content',
		);
	}

	public function get_name() {
		return 'spae-' . $this->catalog_id();
	}

	public function get_title() {
		return $this->meta()['title'];
	}

	public function get_icon() {
		return $this->meta()['icon'];
	}

	public function get_categories() {
		return array( 'primeui' );
	}

	public function get_keywords() {
		return array( 'speedpress', $this->catalog_id(), $this->meta()['type'] );
	}

	public function get_script_depends() {
		$need = array( 'tabs', 'tabs_vertical', 'toggles', 'countdown', 'launch', 'before_after', 'typed_heading', 'marquee', 'ticker', 't_slider', 'logo_slider', 'modal', 'offcanvas', 'coupon', 'hotspots', 'image_acc', 'circle', 'big_counter', 'faq_schema', 'clock' );
		if ( in_array( $this->meta()['type'], $need, true ) ) {
			return array( 'speedpress-addons' );
		}
		return array();
	}

	protected function register_controls() {
		$type = $this->meta()['type'];
		$this->start_controls_section(
			'content',
			array( 'label' => __( 'Content', 'speedpress-addons' ) )
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->meta()['title'],
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Subtitle', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Text', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $this->meta()['description'],
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'Title tag', 'speedpress-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'icon_text',
			array(
				'label'   => __( 'Icon / mark', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '●',
			)
		);

		$this->add_control(
			'image',
			array(
				'label' => __( 'Image', 'speedpress-addons' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'image_b',
			array(
				'label'     => __( 'Second image', 'speedpress-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button text', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Learn more', 'speedpress-addons' ),
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
			'button_text_b',
			array(
				'label'   => __( 'Second button', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Contact', 'speedpress-addons' ),
			)
		);

		$this->add_control(
			'button_link_b',
			array(
				'label' => __( 'Second link', 'speedpress-addons' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'url',
			array(
				'label'       => __( 'URL / embed / phone / email', 'speedpress-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'datetime',
			array(
				'label'   => __( 'Target date', 'speedpress-addons' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => gmdate( 'Y-m-d H:i', time() + WEEK_IN_SECONDS ),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => __( 'Number', 'speedpress-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 80,
			)
		);

		$this->add_control(
			'suffix',
			array(
				'label'   => __( 'Suffix', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '%',
			)
		);

		$this->add_control(
			'html',
			array(
				'label'    => __( 'HTML / shortcode / code', 'speedpress-addons' ),
				'type'     => Controls_Manager::TEXTAREA,
				'default'  => '',
			)
		);

		$this->add_control(
			'alert_type',
			array(
				'label'   => __( 'Tone', 'speedpress-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'info',
				'options' => array(
					'info'    => __( 'Info', 'speedpress-addons' ),
					'success' => __( 'Success', 'speedpress-addons' ),
					'warning' => __( 'Warning', 'speedpress-addons' ),
					'danger'  => __( 'Danger', 'speedpress-addons' ),
				),
			)
		);

		$this->add_control(
			'post_count',
			array(
				'label'   => __( 'Item count', 'speedpress-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 24,
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'item_title',
			array(
				'label'   => __( 'Title', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Item', 'speedpress-addons' ),
			)
		);
		$rep->add_control(
			'item_text',
			array(
				'label'   => __( 'Text', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Short supporting line.', 'speedpress-addons' ),
			)
		);
		$rep->add_control(
			'item_meta',
			array(
				'label'   => __( 'Meta / price / time', 'speedpress-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);
		$rep->add_control(
			'item_image',
			array(
				'label' => __( 'Image', 'speedpress-addons' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
		$rep->add_control(
			'item_url',
			array(
				'label' => __( 'Link', 'speedpress-addons' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Items', 'speedpress-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'default'     => array(
					array(
						'item_title' => __( 'First', 'speedpress-addons' ),
						'item_text'  => __( 'Add your own copy.', 'speedpress-addons' ),
						'item_meta'  => '01',
					),
					array(
						'item_title' => __( 'Second', 'speedpress-addons' ),
						'item_text'  => __( 'Add your own copy.', 'speedpress-addons' ),
						'item_meta'  => '02',
					),
					array(
						'item_title' => __( 'Third', 'speedpress-addons' ),
						'item_text'  => __( 'Add your own copy.', 'speedpress-addons' ),
						'item_meta'  => '03',
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->add_control(
			'gallery',
			array(
				'label' => __( 'Gallery', 'speedpress-addons' ),
				'type'  => Controls_Manager::GALLERY,
			)
		);

		if ( 'menu' === $type ) {
			$menus   = wp_get_nav_menus();
			$options = array( '' => __( '— Select —', 'speedpress-addons' ) );
			foreach ( $menus as $menu ) {
				$options[ $menu->term_id ] = $menu->name;
			}
			$this->add_control(
				'menu_id',
				array(
					'label'   => __( 'Menu', 'speedpress-addons' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $options,
				)
			);
		}

		if ( 'cf7' === $type && function_exists( 'wpcf7' ) ) {
			$forms   = get_posts(
				array(
					'post_type'      => 'wpcf7_contact_form',
					'posts_per_page' => 50,
				)
			);
			$options = array( '' => __( '— Select —', 'speedpress-addons' ) );
			foreach ( $forms as $form ) {
				$options[ $form->ID ] = $form->post_title;
			}
			$this->add_control(
				'form_id',
				array(
					'label'   => __( 'Form', 'speedpress-addons' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $options,
				)
			);
		}

		if ( 'wpforms' === $type && function_exists( 'wpforms' ) ) {
			$forms   = get_posts(
				array(
					'post_type'      => 'wpforms',
					'posts_per_page' => 50,
				)
			);
			$options = array( '' => __( '— Select —', 'speedpress-addons' ) );
			foreach ( $forms as $form ) {
				$options[ $form->ID ] = $form->post_title;
			}
			$this->add_control(
				'form_id',
				array(
					'label'   => __( 'Form', 'speedpress-addons' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $options,
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'style',
			array(
				'label' => __( 'Style', 'speedpress-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Built-in layout', 'primeui' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'l1',
				'options' => Layouts::options( $this->meta()['type'], $this->meta()['title'] ),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'   => __( 'Design', 'speedpress-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'soft',
				'options' => array(
					'soft'     => __( 'Soft card', 'speedpress-addons' ),
					'outline'  => __( 'Outline', 'speedpress-addons' ),
					'dark'     => __( 'Dark', 'speedpress-addons' ),
					'gradient' => __( 'Brand gradient', 'speedpress-addons' ),
					'plain'    => __( 'Plain', 'speedpress-addons' ),
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => __( 'Align', 'speedpress-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array( 'title' => __( 'Left', 'speedpress-addons' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => __( 'Center', 'speedpress-addons' ), 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => __( 'Right', 'speedpress-addons' ), 'icon' => 'eicon-text-align-right' ),
				),
				'default'   => 'left',
				'selectors' => array( '{{WRAPPER}} .spae-w' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'     => __( 'Radius', 'speedpress-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'selectors' => array( '{{WRAPPER}} .spae-w' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'columns',
			array(
				'label'     => __( 'Columns', 'primeui' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .spae-grid, {{WRAPPER}} .spae-steps, {{WRAPPER}} .spae-ig, {{WRAPPER}} .spae-lg' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ),
			)
		);
		$this->add_control(
			'gap',
			array(
				'label'     => __( 'Gap', 'primeui' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'default'   => array( 'size' => 16 ),
				'selectors' => array( '{{WRAPPER}} .spae-grid, {{WRAPPER}} .spae-steps, {{WRAPPER}} .spae-ig' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title color', 'primeui' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .spae-title, {{WRAPPER}} h3, {{WRAPPER}} .spae-dual' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'btn_bg',
			array(
				'label'     => __( 'Button background', 'primeui' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2563EB',
				'selectors' => array( '{{WRAPPER}} .spae-cta__btn, {{WRAPPER}} .spae-cbtn a, {{WRAPPER}} a.spae-price__btn' => 'background: {{VALUE}}; color: #fff;' ),
			)
		);
		$this->add_control(
			'border',
			array(
				'label'     => __( 'Border', 'primeui' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .spae-w' => 'border: 1px solid {{VALUE}};' ),
			)
		);
		$this->add_control(
			'shadow',
			array(
				'label'        => __( 'Shadow', 'primeui' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => array( '{{WRAPPER}} .spae-w' => 'box-shadow: 0 12px 30px rgba(15,23,42,.08);' ),
			)
		);
		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text', 'primeui' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .spae-w' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'bg',
			array(
				'label'     => __( 'Background', 'speedpress-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .spae-w' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'     => __( 'Accent', 'speedpress-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#16A34A',
				'selectors' => array( '{{WRAPPER}} .spae-w' => '--spae-accent: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'pad',
			array(
				'label'      => __( 'Padding', 'speedpress-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array( '{{WRAPPER}} .spae-w' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} .spae-w',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$skin = isset( $s['skin'] ) ? sanitize_html_class( $s['skin'] ) : 'soft';
		$layout = isset( $s['layout'] ) ? sanitize_html_class( $s['layout'] ) : 'l1';
		$type = $this->meta()['type'];
		echo '<div class="spae-w pui-f-' . esc_attr( Layouts::family( $type ) ) . ' pui-layout-' . esc_attr( $layout ) . ' pui-type-' . esc_attr( sanitize_html_class( $type ) ) . ' spae-skin-' . esc_attr( $skin ) . ' spae-w--' . esc_attr( $type ) . '" data-spae="' . esc_attr( $type ) . '">';
		Renderer::render( $this->meta()['type'], $s, $this );
		echo '</div>';
	}
}
