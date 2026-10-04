<?php
namespace SpeedPress\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		require_once PUI_PATH . 'includes/class-catalog.php';
		require_once PUI_PATH . 'includes/class-layouts.php';
		require_once PUI_PATH . 'includes/class-renderer.php';
		require_once PUI_PATH . 'includes/class-widgets-manager.php';

		$enabled = get_option( 'pui_enabled_widgets', null );
		if ( ! is_array( $enabled ) ) {
			update_option( 'pui_enabled_widgets', array_keys( Widgets_Manager::available_widgets() ) );
		}

		add_action( 'elementor/init', array( $this, 'on_elementor_init' ) );
		add_action( 'admin_notices', array( $this, 'maybe_elementor_notice' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function admin_assets( $hook ) {
		if ( false === strpos( (string) $hook, 'primeui' ) ) {
			return;
		}
		wp_enqueue_style( 'primeui-admin', PUI_URL . 'assets/css/admin.css', array(), PUI_VERSION );
		wp_enqueue_script( 'primeui-admin', PUI_URL . 'assets/js/admin.js', array(), PUI_VERSION, true );
	}

	public function maybe_elementor_notice() {
		if ( did_action( 'elementor/loaded' ) ) {
			return;
		}
		spae_missing_elementor_notice();
	}

	public function on_elementor_init() {
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}
		require_once PUI_PATH . 'includes/class-catalog-widget.php';
		Widgets_Manager::instance();
	}

	public function admin_menu() {
		add_menu_page( 'PrimeUI', 'PrimeUI', 'manage_options', 'primeui', array( $this, 'render_settings_page' ), 'dashicons-layout', 58 );
		add_submenu_page( 'primeui', 'Widgets', 'Widgets', 'manage_options', 'primeui', array( $this, 'render_settings_page' ) );
	}

	public function register_settings() {
		register_setting( 'pui_settings_group', 'pui_enabled_widgets', array(
			'type'              => 'array',
			'sanitize_callback' => array( $this, 'sanitize_widgets' ),
			'default'           => array_keys( Widgets_Manager::available_widgets() ),
		) );
	}

	public function sanitize_widgets( $value ) {
		if ( ! is_array( $value ) ) {
			return array();
		}
		return array_values( array_intersect( array_keys( Widgets_Manager::available_widgets() ), array_map( 'sanitize_key', $value ) ) );
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$widgets = Widgets_Manager::available_widgets();
		$groups  = Catalog::groups();
		$enabled = get_option( 'pui_enabled_widgets', array_keys( $widgets ) );
		if ( ! is_array( $enabled ) ) {
			$enabled = array_keys( $widgets );
		}
		?>
		<div class="wrap spae-app">
			<div class="spae-hero">
				<div class="spae-brand">
					<div class="spae-mark">UI</div>
					<div>
						<div class="spae-kicker">Free Elementor widgets</div>
						<h1>PrimeUI Widgets</h1>
						<p>All widgets are free. Style them in Elementor with design, columns, colors, type, spacing, and shadow.</p>
					</div>
				</div>
			</div>
			<form method="post" action="options.php">
				<?php settings_fields( 'pui_settings_group' ); ?>
				<div class="spae-toolbar">
					<input id="spae-search" class="spae-search" type="search" placeholder="Search widgets" />
					<div class="spae-pills">
						<button type="button" class="spae-pillbtn is-on" data-group="all">All</button>
						<?php foreach ( $groups as $gid => $label ) : ?>
							<button type="button" class="spae-pillbtn" data-group="<?php echo esc_attr( $gid ); ?>"><?php echo esc_html( $label ); ?></button>
						<?php endforeach; ?>
					</div>
					<button type="button" class="spae-btn" id="spae-all">Enable visible</button>
					<button type="button" class="spae-btn" id="spae-none">Disable visible</button>
				</div>
				<div class="spae-grid-admin">
					<?php foreach ( $widgets as $id => $meta ) : ?>
						<?php $gid = $meta['group'] ?? 'content'; $on = in_array( $id, $enabled, true ); ?>
						<article class="spae-wcard<?php echo $on ? '' : ' is-off'; ?>" data-group="<?php echo esc_attr( $gid ); ?>" data-search="<?php echo esc_attr( $meta['title'] . ' ' . $meta['description'] ); ?>">
							<label class="spae-switch"><input class="spae-toggle" type="checkbox" name="pui_enabled_widgets[]" value="<?php echo esc_attr( $id ); ?>" <?php checked( $on ); ?> /><span></span></label>
							<div>
								<h3><?php echo esc_html( $meta['title'] ); ?></h3>
								<p><?php echo esc_html( $meta['description'] ); ?></p>
								<span class="spae-tag"><?php echo esc_html( $groups[ $gid ] ?? $gid ); ?></span>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="spae-savebar"><span>Save, then open the PrimeUI category in Elementor.</span><?php submit_button( 'Save widgets', 'primary', 'submit', false ); ?></div>
			</form>
		</div>
		<?php
	}
}
