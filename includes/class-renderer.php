<?php
namespace SpeedPress\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Renderer {

	public static function render( $type, $s, $widget ) {
		$method = 'type_' . $type;
		if ( method_exists( __CLASS__, $method ) ) {
			self::$method( $s );
			return;
		}
		self::type_generic( $s );
	}

	private static function items( $s ) {
		return ( ! empty( $s['items'] ) && is_array( $s['items'] ) ) ? $s['items'] : array();
	}

	private static function img( $field, $alt = '' ) {
		if ( empty( $field['url'] ) ) {
			return '';
		}
		return '<img src="' . esc_url( $field['url'] ) . '" alt="' . esc_attr( $alt ) . '" />';
	}

	private static function link_open( $url_field ) {
		$url = isset( $url_field['url'] ) ? $url_field['url'] : '';
		if ( ! $url ) {
			return '';
		}
		$tgt = ! empty( $url_field['is_external'] ) ? ' target="_blank"' : '';
		$rel = ! empty( $url_field['nofollow'] ) ? ' rel="nofollow"' : '';
		return '<a href="' . esc_url( $url ) . '"' . $tgt . $rel . '>';
	}

	private static function heading( $s ) {
		$tag = isset( $s['title_tag'] ) ? $s['title_tag'] : 'h3';
		$ok  = array( 'h1', 'h2', 'h3', 'h4', 'div', 'span' );
		if ( ! in_array( $tag, $ok, true ) ) {
			$tag = 'h3';
		}
		if ( empty( $s['title'] ) ) {
			return '';
		}
		return '<' . $tag . ' class="spae-title">' . wp_kses_post( $s['title'] ) . '</' . $tag . '>';
	}

	private static function btn( $s, $key = 'button' ) {
		$text = isset( $s[ $key . '_text' ] ) ? $s[ $key . '_text' ] : '';
		$link = isset( $s[ $key . '_link' ] ) ? $s[ $key . '_link' ] : array();
		$open = self::link_open( $link );
		if ( ! $text || ! $open ) {
			return '';
		}
		return $open . esc_html( $text ) . '</a>';
	}

	private static function type_generic( $s ) {
		echo self::heading( $s ); // phpcs:ignore
		if ( ! empty( $s['subtitle'] ) ) {
			echo '<p class="spae-sub">' . esc_html( $s['subtitle'] ) . '</p>';
		}
		if ( ! empty( $s['text'] ) ) {
			echo '<p>' . wp_kses_post( $s['text'] ) . '</p>';
		}
		echo self::btn( $s ); // phpcs:ignore
	}

	private static function type_dual_heading( $s ) {
		echo '<p class="spae-dual"><span>' . esc_html( $s['title'] ) . '</span> <span class="spae-accent">' . esc_html( $s['subtitle'] ) . '</span></p>';
	}

	private static function type_gradient_heading( $s ) {
		echo '<p class="spae-grad">' . esc_html( $s['title'] ) . '</p>';
	}

	private static function type_typed_heading( $s ) {
		$words = array_filter( array_map( 'trim', explode( ',', (string) $s['text'] ) ) );
		if ( ! $words ) {
			$words = array( 'faster', 'safer', 'clearer' );
		}
		echo '<p class="spae-typed">' . esc_html( $s['title'] ) . ' <span data-words="' . esc_attr( wp_json_encode( array_values( $words ) ) ) . '">' . esc_html( $words[0] ) . '</span></p>';
	}

	private static function type_highlight_heading( $s ) {
		echo '<p class="spae-hl">' . esc_html( $s['title'] ) . ' <mark>' . esc_html( $s['subtitle'] ? $s['subtitle'] : $s['text'] ) . '</mark></p>';
	}

	private static function type_drop_cap( $s ) {
		echo '<p class="spae-drop">' . wp_kses_post( $s['text'] ) . '</p>';
	}

	private static function type_quote( $s ) {
		echo '<blockquote class="spae-bq"><p>' . esc_html( $s['text'] ) . '</p><cite>' . esc_html( $s['title'] ) . '</cite></blockquote>';
	}

	private static function type_icon_box( $s ) {
		echo '<div class="spae-ibox">';
		echo '<div class="spae-ibox__icon">' . esc_html( $s['icon_text'] ) . '</div>';
		echo self::heading( $s ); // phpcs:ignore
		echo '<p>' . esc_html( $s['text'] ) . '</p>';
		echo self::btn( $s ); // phpcs:ignore
		echo '</div>';
	}

	private static function type_image_box( $s ) {
		echo '<div class="spae-ibox">';
		echo self::img( $s['image'], $s['title'] ); // phpcs:ignore
		echo self::heading( $s ); // phpcs:ignore
		echo '<p>' . esc_html( $s['text'] ) . '</p>';
		echo '</div>';
	}

	private static function type_hover_card( $s ) {
		echo '<div class="spae-hover">';
		echo self::heading( $s ); // phpcs:ignore
		echo '<p>' . esc_html( $s['text'] ) . '</p>';
		echo '<div class="spae-hover__go">' . self::btn( $s ) . '</div>'; // phpcs:ignore
		echo '</div>';
	}

	private static function type_flip_card( $s ) {
		echo '<div class="spae-flip"><div class="spae-flip__inner">';
		echo '<div class="spae-flip__front">' . self::heading( $s ) . '<p>' . esc_html( $s['subtitle'] ) . '</p></div>'; // phpcs:ignore
		echo '<div class="spae-flip__back"><p>' . esc_html( $s['text'] ) . '</p>' . self::btn( $s ) . '</div>'; // phpcs:ignore
		echo '</div></div>';
	}

	private static function type_overlay_card( $s ) {
		echo '<div class="spae-ov">';
		echo self::img( $s['image'], $s['title'] ); // phpcs:ignore
		echo '<div class="spae-ov__txt">' . self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p></div>'; // phpcs:ignore
		echo '</div>';
	}

	private static function type_feature_list( $s ) {
		echo '<ul class="spae-ul">';
		foreach ( self::items( $s ) as $item ) {
			echo '<li><strong>' . esc_html( $item['item_title'] ) . '</strong><span>' . esc_html( $item['item_text'] ) . '</span></li>';
		}
		echo '</ul>';
	}

	private static function type_check_list( $s ) {
		echo '<ul class="spae-check">';
		foreach ( self::items( $s ) as $item ) {
			echo '<li><span>' . esc_html( $s['icon_text'] ? $s['icon_text'] : '✓' ) . '</span> ' . esc_html( $item['item_title'] ) . '</li>';
		}
		echo '</ul>';
	}

	private static function type_numbered_list( $s ) {
		echo '<ol class="spae-ol">';
		foreach ( self::items( $s ) as $item ) {
			echo '<li><strong>' . esc_html( $item['item_title'] ) . '</strong> ' . esc_html( $item['item_text'] ) . '</li>';
		}
		echo '</ol>';
	}

	private static function type_icon_list( $s ) {
		self::type_check_list( $s );
	}

	private static function type_steps( $s ) {
		echo '<div class="spae-steps">';
		foreach ( self::items( $s ) as $i => $item ) {
			echo '<div class="spae-step"><span>' . esc_html( $item['item_meta'] ? $item['item_meta'] : str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ) . '</span><strong>' . esc_html( $item['item_title'] ) . '</strong><p>' . esc_html( $item['item_text'] ) . '</p></div>';
		}
		echo '</div>';
	}

	private static function type_timeline( $s ) {
		echo '<div class="spae-time">';
		foreach ( self::items( $s ) as $item ) {
			echo '<div class="spae-time__row"><div class="spae-time__meta">' . esc_html( $item['item_meta'] ) . '</div><div><strong>' . esc_html( $item['item_title'] ) . '</strong><p>' . esc_html( $item['item_text'] ) . '</p></div></div>';
		}
		echo '</div>';
	}

	private static function type_h_timeline( $s ) {
		echo '<div class="spae-htime">';
		foreach ( self::items( $s ) as $item ) {
			echo '<div><em>' . esc_html( $item['item_meta'] ) . '</em><strong>' . esc_html( $item['item_title'] ) . '</strong><p>' . esc_html( $item['item_text'] ) . '</p></div>';
		}
		echo '</div>';
	}

	private static function type_tabs( $s ) {
		self::tabs_markup( $s, false );
	}

	private static function type_tabs_vertical( $s ) {
		self::tabs_markup( $s, true );
	}

	private static function tabs_markup( $s, $vert ) {
		echo '<div class="spae-tabs' . ( $vert ? ' is-vert' : '' ) . '">';
		echo '<div class="spae-tabs__nav" role="tablist">';
		foreach ( self::items( $s ) as $i => $item ) {
			echo '<button type="button" class="spae-tab" data-i="' . esc_attr( $i ) . '" aria-selected="' . ( 0 === $i ? 'true' : 'false' ) . '">' . esc_html( $item['item_title'] ) . '</button>';
		}
		echo '</div><div class="spae-tabs__panels">';
		foreach ( self::items( $s ) as $i => $item ) {
			echo '<div class="spae-panel"' . ( 0 === $i ? '' : ' hidden' ) . '>' . wp_kses_post( wpautop( $item['item_text'] ) ) . '</div>';
		}
		echo '</div></div>';
	}

	private static function type_toggles( $s ) {
		echo '<div class="spae-toggles">';
		foreach ( self::items( $s ) as $i => $item ) {
			echo '<div class="spae-faq__item"><button type="button" class="spae-faq__q" aria-expanded="false">' . esc_html( $item['item_title'] ) . '</button><div class="spae-faq__a" hidden>' . wp_kses_post( wpautop( $item['item_text'] ) ) . '</div></div>';
		}
		echo '</div>';
	}

	private static function type_tooltip( $s ) {
		echo '<span class="spae-tip">' . esc_html( $s['title'] ) . '<span class="spae-tip__box">' . esc_html( $s['text'] ) . '</span></span>';
	}

	private static function type_alert( $s ) {
		$tone = isset( $s['alert_type'] ) ? $s['alert_type'] : 'info';
		echo '<div class="spae-alert is-' . esc_attr( $tone ) . '"><strong>' . esc_html( $s['title'] ) . '</strong> ' . esc_html( $s['text'] ) . '</div>';
	}

	private static function type_notice( $s ) {
		self::type_alert( $s );
	}

	private static function type_badge( $s ) {
		echo '<span class="spae-pill">' . esc_html( $s['title'] ) . '</span>';
	}

	private static function type_ribbon( $s ) {
		echo '<div class="spae-ribbon-wrap">' . self::img( $s['image'], $s['title'] ) . '<span class="spae-ribbon">' . esc_html( $s['title'] ) . '</span></div>'; // phpcs:ignore
	}

	private static function type_divider_label( $s ) {
		echo '<div class="spae-div"><span>' . esc_html( $s['title'] ) . '</span></div>';
	}

	private static function type_shape_sep( $s ) {
		echo '<div class="spae-shape" aria-hidden="true"></div>';
	}

	private static function type_anim_link( $s ) {
		$open = self::link_open( $s['button_link'] );
		if ( $open ) {
			echo $open . '<span class="spae-alink">' . esc_html( $s['title'] ) . '</span></a>'; // phpcs:ignore
		} else {
			echo '<span class="spae-alink">' . esc_html( $s['title'] ) . '</span>';
		}
	}

	private static function type_image_caption( $s ) {
		echo '<figure class="spae-fig">' . self::img( $s['image'], $s['title'] ) . '<figcaption>' . esc_html( $s['title'] ) . '</figcaption></figure>'; // phpcs:ignore
	}

	private static function type_image_overlay( $s ) {
		self::type_overlay_card( $s );
	}

	private static function gallery_loop( $s, $class ) {
		echo '<div class="' . esc_attr( $class ) . '">';
		if ( ! empty( $s['gallery'] ) ) {
			foreach ( $s['gallery'] as $img ) {
				echo '<a href="' . esc_url( $img['url'] ) . '"><img src="' . esc_url( $img['url'] ) . '" alt="" /></a>';
			}
		} else {
			foreach ( self::items( $s ) as $item ) {
				if ( ! empty( $item['item_image']['url'] ) ) {
					echo self::img( $item['item_image'], $item['item_title'] ); // phpcs:ignore
				}
			}
		}
		echo '</div>';
	}

	private static function type_image_grid( $s ) {
		self::gallery_loop( $s, 'spae-ig' );
	}

	private static function type_logo_grid( $s ) {
		self::gallery_loop( $s, 'spae-lg' );
	}

	private static function type_logo_cloud( $s ) {
		self::gallery_loop( $s, 'spae-lg is-cloud' );
	}

	private static function type_logo_slider( $s ) {
		echo '<div class="spae-marquee">';
		self::gallery_loop( $s, 'spae-lg' );
		echo '</div>';
	}

	private static function type_video( $s ) {
		$url = isset( $s['url'] ) ? $s['url'] : '';
		if ( ! $url ) {
			echo '<p>' . esc_html__( 'Add a YouTube or Vimeo URL.', 'speedpress-addons' ) . '</p>';
			return;
		}
		$embed = wp_oembed_get( $url );
		echo $embed ? $embed : '<a href="' . esc_url( $url ) . '">' . esc_html( $url ) . '</a>'; // phpcs:ignore
	}

	private static function type_video_popup( $s ) {
		$url = isset( $s['url'] ) ? $s['url'] : '#';
		echo '<a class="spae-vp" href="' . esc_url( $url ) . '" target="_blank" rel="noopener">';
		echo self::img( $s['image'], $s['title'] ); // phpcs:ignore
		echo '<span>' . esc_html( $s['title'] ? $s['title'] : __( 'Play', 'speedpress-addons' ) ) . '</span></a>';
	}

	private static function type_before_after( $s ) {
		echo '<div class="spae-ba">';
		echo '<div class="spae-ba__a">' . self::img( $s['image'], 'before' ) . '</div>'; // phpcs:ignore
		echo '<div class="spae-ba__b">' . self::img( $s['image_b'], 'after' ) . '</div>'; // phpcs:ignore
		echo '<input type="range" min="0" max="100" value="50" class="spae-ba__r" />';
		echo '</div>';
	}

	private static function type_hotspots( $s ) {
		echo '<div class="spae-hs">' . self::img( $s['image'], $s['title'] ); // phpcs:ignore
		foreach ( self::items( $s ) as $item ) {
			echo '<button type="button" class="spae-hs__dot" title="' . esc_attr( $item['item_title'] ) . '">' . esc_html( $item['item_meta'] ? $item['item_meta'] : '+' ) . '<span>' . esc_html( $item['item_text'] ) . '</span></button>';
		}
		echo '</div>';
	}

	private static function type_photo_stack( $s ) {
		echo '<div class="spae-stack">';
		self::gallery_loop( $s, 'spae-stack__in' );
		echo '</div>';
	}

	private static function type_polaroid( $s ) {
		echo '<figure class="spae-pol">' . self::img( $s['image'], $s['title'] ) . '<figcaption>' . esc_html( $s['title'] ) . '</figcaption></figure>'; // phpcs:ignore
	}

	private static function type_device( $s ) {
		echo '<div class="spae-device">' . self::img( $s['image'], $s['title'] ) . '</div>'; // phpcs:ignore
	}

	private static function type_gallery( $s ) {
		self::gallery_loop( $s, 'spae-ig' );
	}

	private static function type_image_acc( $s ) {
		echo '<div class="spae-iacc">';
		foreach ( self::items( $s ) as $i => $item ) {
			$src = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
			echo '<button type="button" class="spae-iacc__p' . ( 0 === $i ? ' is-on' : '' ) . '" style="background-image:url(' . esc_url( $src ) . ')"><span>' . esc_html( $item['item_title'] ) . '</span></button>';
		}
		echo '</div>';
	}

	private static function type_dual_button( $s ) {
		echo '<div class="spae-db">' . self::btn( $s ) . self::btn( $s, 'button_b' ) . '</div>'; // phpcs:ignore
	}

	private static function type_creative_button( $s ) {
		echo '<div class="spae-cbtn">' . self::btn( $s ) . '</div>'; // phpcs:ignore
	}

	private static function type_download_btn( $s ) {
		self::type_creative_button( $s );
	}

	private static function type_call_btn( $s ) {
		$tel = isset( $s['url'] ) ? $s['url'] : '';
		echo '<a class="spae-cta__btn" href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $tel ) ) . '">' . esc_html( $s['button_text'] ? $s['button_text'] : $tel ) . '</a>';
	}

	private static function type_promo( $s ) {
		echo '<div class="spae-promo">' . self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p>' . self::btn( $s ) . '</div>'; // phpcs:ignore
	}

	private static function type_offer( $s ) {
		echo '<div class="spae-offer"><div class="spae-offer__n">' . esc_html( $s['number'] ) . esc_html( $s['suffix'] ) . '</div>';
		echo self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p></div>'; // phpcs:ignore
	}

	private static function type_coupon( $s ) {
		$code = $s['title'] ? $s['title'] : 'SPEED10';
		echo '<button type="button" class="spae-coupon" data-copy="' . esc_attr( $code ) . '">' . esc_html( $code ) . '</button>';
		echo '<p>' . esc_html( $s['text'] ) . '</p>';
	}

	private static function type_countdown( $s ) {
		echo '<div class="spae-cd" data-end="' . esc_attr( $s['datetime'] ) . '"><span data-u="d">0</span>d <span data-u="h">0</span>h <span data-u="m">0</span>m <span data-u="s">0</span>s</div>';
	}

	private static function type_launch( $s ) {
		echo self::heading( $s ); // phpcs:ignore
		self::type_countdown( $s );
	}

	private static function type_price_list( $s ) {
		echo '<ul class="spae-pl">';
		foreach ( self::items( $s ) as $item ) {
			echo '<li><span>' . esc_html( $item['item_title'] ) . '</span><i></i><strong>' . esc_html( $item['item_meta'] ) . '</strong></li>';
		}
		echo '</ul>';
	}

	private static function type_compare( $s ) {
		echo '<table class="spae-table"><thead><tr><th>' . esc_html( $s['title'] ) . '</th><th>' . esc_html( $s['subtitle'] ) . '</th><th>' . esc_html( $s['button_text'] ) . '</th></tr></thead><tbody>';
		foreach ( self::items( $s ) as $item ) {
			echo '<tr><td>' . esc_html( $item['item_title'] ) . '</td><td>' . esc_html( $item['item_text'] ) . '</td><td>' . esc_html( $item['item_meta'] ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}

	private static function type_price_switch( $s ) {
		echo '<div class="spae-ps">';
		echo '<div class="spae-price"><div class="spae-price__plan">' . esc_html( $s['title'] ) . '</div><div class="spae-price__amount">' . esc_html( $s['subtitle'] ) . '</div><p>' . esc_html( $s['text'] ) . '</p></div>';
		echo '</div>';
	}

	private static function type_cta_split( $s ) {
		echo '<div class="spae-split">' . self::heading( $s ) . '<div>' . self::btn( $s ) . '</div></div>'; // phpcs:ignore
	}

	private static function type_newsletter( $s ) {
		echo self::heading( $s ); // phpcs:ignore
		if ( ! empty( $s['html'] ) ) {
			echo do_shortcode( $s['html'] );
		} else {
			echo '<form class="spae-news" action="#" method="post"><input type="email" name="email" placeholder="you@example.com" /><button type="submit">' . esc_html( $s['button_text'] ) . '</button></form>';
		}
	}

	private static function type_lead( $s ) {
		self::type_newsletter( $s );
	}

	private static function type_modal( $s ) {
		echo '<button type="button" class="spae-cta__btn spae-modal-open">' . esc_html( $s['button_text'] ) . '</button>';
		echo '<dialog class="spae-dialog">';
		echo self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p>'; // phpcs:ignore
		echo '<button type="button" class="spae-modal-close">' . esc_html__( 'Close', 'speedpress-addons' ) . '</button>';
		echo '</dialog>';
	}

	private static function type_review( $s ) {
		echo '<div class="spae-quote"><div class="spae-quote__stars">★★★★★</div><blockquote>' . esc_html( $s['text'] ) . '</blockquote><figcaption>' . esc_html( $s['title'] ) . '</figcaption></div>';
	}

	private static function type_rating_badge( $s ) {
		echo '<div class="spae-rb"><strong>' . esc_html( $s['number'] ) . '</strong><span>' . esc_html( $s['title'] ) . '</span></div>';
	}

	private static function type_t_slider( $s ) {
		echo '<div class="spae-ts">';
		foreach ( self::items( $s ) as $i => $item ) {
			echo '<figure class="spae-quote"' . ( 0 === $i ? '' : ' hidden' ) . '><blockquote>' . esc_html( $item['item_text'] ) . '</blockquote><figcaption>' . esc_html( $item['item_title'] ) . '</figcaption></figure>';
		}
		echo '<div class="spae-ts__nav"><button type="button" data-dir="-1">‹</button><button type="button" data-dir="1">›</button></div></div>';
	}

	private static function type_team( $s ) {
		echo '<div class="spae-team">' . self::img( $s['image'], $s['title'] ) . self::heading( $s ) . '<p class="spae-sub">' . esc_html( $s['subtitle'] ) . '</p><p>' . esc_html( $s['text'] ) . '</p></div>'; // phpcs:ignore
	}

	private static function type_team_grid( $s ) {
		echo '<div class="spae-grid">';
		foreach ( self::items( $s ) as $item ) {
			echo '<div class="spae-card">' . self::img( $item['item_image'], $item['item_title'] ) . '<h3>' . esc_html( $item['item_title'] ) . '</h3><p>' . esc_html( $item['item_text'] ) . '</p></div>'; // phpcs:ignore
		}
		echo '</div>';
	}

	private static function type_case_study( $s ) {
		echo '<div class="spae-case"><div class="spae-stat__num">' . esc_html( $s['number'] ) . esc_html( $s['suffix'] ) . '</div>' . self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p></div>'; // phpcs:ignore
	}

	private static function type_stars( $s ) {
		$n = max( 0, min( 5, (int) $s['number'] ) );
		echo '<div class="spae-quote__stars">';
		for ( $i = 0; $i < $n; $i++ ) {
			echo '★';
		}
		echo '</div>';
	}

	private static function type_progress( $s ) {
		$n = (float) $s['number'];
		echo '<div class="spae-bar"><span>' . esc_html( $s['title'] ) . '</span><div class="spae-bar__track"><i style="width:' . esc_attr( $n ) . '%"></i></div></div>';
	}

	private static function type_skills( $s ) {
		foreach ( self::items( $s ) as $item ) {
			$n = is_numeric( $item['item_meta'] ) ? $item['item_meta'] : 70;
			echo '<div class="spae-bar"><span>' . esc_html( $item['item_title'] ) . '</span><div class="spae-bar__track"><i style="width:' . esc_attr( $n ) . '%"></i></div></div>';
		}
	}

	private static function type_circle( $s ) {
		$n = (float) $s['number'];
		echo '<div class="spae-circle" style="--p:' . esc_attr( $n ) . '"><span>' . esc_html( $n ) . esc_html( $s['suffix'] ) . '</span></div>';
	}

	private static function type_big_counter( $s ) {
		echo '<div class="spae-stat__num"><span class="spae-count" data-target="' . esc_attr( $s['number'] ) . '">0</span>' . esc_html( $s['suffix'] ) . '</div><div class="spae-stat__label">' . esc_html( $s['title'] ) . '</div>';
	}

	private static function type_stat_card( $s ) {
		self::type_big_counter( $s );
		echo '<p>' . esc_html( $s['text'] ) . '</p>';
	}

	private static function type_pie( $s ) {
		self::type_circle( $s );
	}

	private static function type_bars( $s ) {
		self::type_skills( $s );
	}

	private static function type_table( $s ) {
		self::type_compare( $s );
	}

	private static function type_specs( $s ) {
		echo '<dl class="spae-dl">';
		foreach ( self::items( $s ) as $item ) {
			echo '<div><dt>' . esc_html( $item['item_title'] ) . '</dt><dd>' . esc_html( $item['item_text'] ) . '</dd></div>';
		}
		echo '</dl>';
	}

	private static function type_site_logo( $s ) {
		if ( ! empty( $s['image']['url'] ) ) {
			echo self::img( $s['image'], get_bloginfo( 'name' ) ); // phpcs:ignore
			return;
		}
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
			return;
		}
		echo '<strong>' . esc_html( get_bloginfo( 'name' ) ) . '</strong>';
	}

	private static function type_site_title( $s ) {
		echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
	}

	private static function type_site_tagline( $s ) {
		echo esc_html( get_bloginfo( 'description' ) );
	}

	private static function type_page_title( $s ) {
		echo '<h1 class="spae-title">' . esc_html( wp_get_document_title() ) . '</h1>';
	}

	private static function type_breadcrumbs( $s ) {
		echo '<nav class="spae-bc"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'speedpress-addons' ) . '</a>';
		if ( is_singular() ) {
			echo ' / <span>' . esc_html( get_the_title() ) . '</span>';
		} else {
			echo ' / <span>' . esc_html( wp_get_document_title() ) . '</span>';
		}
		echo '</nav>';
	}

	private static function type_menu( $s ) {
		$id = isset( $s['menu_id'] ) ? (int) $s['menu_id'] : 0;
		if ( $id ) {
			wp_nav_menu(
				array(
					'menu'       => $id,
					'container'  => 'nav',
					'menu_class' => 'spae-menu',
				)
			);
			return;
		}
		echo '<p>' . esc_html__( 'Select a menu.', 'speedpress-addons' ) . '</p>';
	}

	private static function type_search( $s ) {
		get_search_form();
	}

	private static function type_author( $s ) {
		if ( ! is_singular() ) {
			echo '<p>' . esc_html__( 'Author box shows on single posts.', 'speedpress-addons' ) . '</p>';
			return;
		}
		echo '<div class="spae-author">' . get_avatar( get_the_author_meta( 'ID' ), 64 );
		echo '<div><strong>' . esc_html( get_the_author() ) . '</strong><p>' . esc_html( get_the_author_meta( 'description' ) ) . '</p></div></div>';
	}

	private static function type_post_meta( $s ) {
		if ( ! is_singular() ) {
			return;
		}
		echo '<div class="spae-meta">' . esc_html( get_the_date() ) . ' · ' . esc_html( get_the_author() ) . '</div>';
	}

	private static function posts_query( $s, $class ) {
		$q = new \WP_Query(
			array(
				'posts_per_page' => isset( $s['post_count'] ) ? (int) $s['post_count'] : 6,
				'post_status'    => 'publish',
			)
		);
		echo '<div class="' . esc_attr( $class ) . '">';
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				echo '<article class="spae-card">';
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'medium' );
				}
				echo '<h3><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
				echo '<p>' . esc_html( wp_trim_words( get_the_excerpt(), 18 ) ) . '</p>';
				echo '</article>';
			}
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__( 'No posts yet.', 'speedpress-addons' ) . '</p>';
		}
		echo '</div>';
	}

	private static function type_posts_grid( $s ) {
		self::posts_query( $s, 'spae-grid' );
	}

	private static function type_posts_list( $s ) {
		self::posts_query( $s, 'spae-plist' );
	}

	private static function type_posts_tiles( $s ) {
		self::posts_query( $s, 'spae-grid' );
	}

	private static function type_cats( $s ) {
		$cats = get_categories( array( 'number' => isset( $s['post_count'] ) ? (int) $s['post_count'] : 8 ) );
		echo '<div class="spae-grid">';
		foreach ( $cats as $cat ) {
			echo '<a class="spae-card" href="' . esc_url( get_category_link( $cat ) ) . '"><strong>' . esc_html( $cat->name ) . '</strong><p>' . esc_html( $cat->count ) . '</p></a>';
		}
		echo '</div>';
	}

	private static function type_archive_head( $s ) {
		echo '<h1 class="spae-title">' . esc_html( wp_get_document_title() ) . '</h1>';
	}

	private static function type_read_time( $s ) {
		$content = get_post_field( 'post_content', get_the_ID() );
		$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
		$mins    = max( 1, (int) ceil( $words / 200 ) );
		echo '<span>' . esc_html( sprintf( __( '%d min read', 'speedpress-addons' ), $mins ) ) . '</span>';
	}

	private static function type_toc( $s ) {
		$content = get_post_field( 'post_content', get_the_ID() );
		if ( ! $content || ! preg_match_all( '/<h([2-3])[^>]*>(.*?)<\/h\1>/i', $content, $m ) ) {
			echo '<p>' . esc_html__( 'No headings found in this post.', 'speedpress-addons' ) . '</p>';
			return;
		}
		echo '<ol class="spae-toc">';
		foreach ( $m[2] as $i => $label ) {
			echo '<li><a href="#spae-h-' . esc_attr( $i ) . '">' . wp_strip_all_tags( $label ) . '</a></li>';
		}
		echo '</ol>';
	}

	private static function type_hours( $s ) {
		echo '<ul class="spae-hours">';
		foreach ( self::items( $s ) as $item ) {
			echo '<li><span>' . esc_html( $item['item_title'] ) . '</span><strong>' . esc_html( $item['item_meta'] ? $item['item_meta'] : $item['item_text'] ) . '</strong></li>';
		}
		echo '</ul>';
	}

	private static function type_contact( $s ) {
		echo '<address class="spae-addr"><strong>' . esc_html( $s['title'] ) . '</strong><br />' . nl2br( esc_html( $s['text'] ) ) . '</address>';
	}

	private static function type_address( $s ) {
		self::type_contact( $s );
	}

	private static function type_map( $s ) {
		$q = rawurlencode( $s['url'] ? $s['url'] : $s['text'] );
		echo '<iframe class="spae-map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=' . $q . '&output=embed"></iframe>'; // phpcs:ignore
	}

	private static function type_phone_bar( $s ) {
		self::type_call_btn( $s );
	}

	private static function type_whatsapp( $s ) {
		$n = preg_replace( '/\D/', '', (string) $s['url'] );
		echo '<a class="spae-cta__btn" href="https://wa.me/' . esc_attr( $n ) . '" target="_blank" rel="noopener">' . esc_html( $s['button_text'] ? $s['button_text'] : 'WhatsApp' ) . '</a>';
	}

	private static function type_open_status( $s ) {
		echo '<span class="spae-pill">' . esc_html( $s['title'] ? $s['title'] : __( 'Open today', 'speedpress-addons' ) ) . '</span>';
	}

	private static function type_service_area( $s ) {
		self::type_check_list( $s );
	}

	private static function type_appt( $s ) {
		self::type_promo( $s );
	}

	private static function type_cf7( $s ) {
		$id = isset( $s['form_id'] ) ? (int) $s['form_id'] : 0;
		if ( $id ) {
			echo do_shortcode( '[contact-form-7 id="' . $id . '"]' );
			return;
		}
		echo '<p>' . esc_html__( 'Install Contact Form 7 and select a form.', 'speedpress-addons' ) . '</p>';
	}

	private static function type_wpforms( $s ) {
		$id = isset( $s['form_id'] ) ? (int) $s['form_id'] : 0;
		if ( $id ) {
			echo do_shortcode( '[wpforms id="' . $id . '"]' );
			return;
		}
		echo '<p>' . esc_html__( 'Install WPForms and select a form.', 'speedpress-addons' ) . '</p>';
	}

	private static function type_shortcode( $s ) {
		echo do_shortcode( $s['html'] ? $s['html'] : $s['text'] );
	}

	private static function type_login( $s ) {
		if ( is_user_logged_in() ) {
			echo '<p>' . esc_html__( 'You are logged in.', 'speedpress-addons' ) . '</p>';
			return;
		}
		wp_login_form( array( 'echo' => true ) );
	}

	private static function type_html_embed( $s ) {
		echo wp_kses_post( $s['html'] ? $s['html'] : $s['text'] );
	}

	private static function type_code( $s ) {
		echo '<pre class="spae-code"><code>' . esc_html( $s['html'] ? $s['html'] : $s['text'] ) . '</code></pre>';
	}

	private static function type_pdf( $s ) {
		$url = ! empty( $s['url'] ) ? $s['url'] : ( isset( $s['button_link']['url'] ) ? $s['button_link']['url'] : '' );
		echo '<a class="spae-cta__btn" href="' . esc_url( $url ) . '">' . esc_html( $s['button_text'] ? $s['button_text'] : 'PDF' ) . '</a>';
	}

	private static function type_woo_products( $s ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<p>' . esc_html__( 'Activate WooCommerce to use this widget.', 'speedpress-addons' ) . '</p>';
			return;
		}
		echo do_shortcode( '[products limit="' . (int) $s['post_count'] . '" columns="3"]' );
	}

	private static function type_woo_cats( $s ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<p>' . esc_html__( 'Activate WooCommerce to use this widget.', 'speedpress-addons' ) . '</p>';
			return;
		}
		echo do_shortcode( '[product_categories number="' . (int) $s['post_count'] . '"]' );
	}

	private static function type_woo_atc( $s ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<p>' . esc_html__( 'Activate WooCommerce to use this widget.', 'speedpress-addons' ) . '</p>';
			return;
		}
		$id = (int) $s['number'];
		if ( $id ) {
			echo do_shortcode( '[add_to_cart id="' . $id . '"]' );
		}
	}

	private static function type_woo_sale( $s ) {
		echo '<span class="spae-pill">' . esc_html( $s['title'] ? $s['title'] : __( 'Sale', 'speedpress-addons' ) ) . '</span>';
	}

	private static function type_ticker( $s ) {
		echo '<div class="spae-ticker"><div>';
		foreach ( self::items( $s ) as $item ) {
			echo '<span>' . esc_html( $item['item_title'] ) . '</span>';
		}
		echo '</div></div>';
	}

	private static function type_marquee( $s ) {
		echo '<div class="spae-marquee"><span>' . esc_html( $s['text'] ? $s['text'] : $s['title'] ) . '</span></div>';
	}

	private static function type_clock( $s ) {
		$tz = $s['text'] ? $s['text'] : wp_timezone_string();
		echo '<div class="spae-clock" data-tz="' . esc_attr( $tz ) . '"></div>';
	}

	private static function type_weather( $s ) {
		echo '<div class="spae-weather">' . esc_html( $s['title'] ) . ' — ' . esc_html( $s['text'] ) . '</div>';
	}

	private static function type_top( $s ) {
		echo '<a class="spae-top" href="#">' . esc_html( $s['title'] ? $s['title'] : '↑' ) . '</a>';
	}

	private static function type_cookie( $s ) {
		echo '<div class="spae-alert">' . esc_html( $s['text'] ) . ' ' . self::btn( $s ) . '</div>'; // phpcs:ignore
	}

	private static function type_age( $s ) {
		echo '<div class="spae-alert is-warning">' . self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p>' . self::btn( $s ) . '</div>'; // phpcs:ignore
	}

	private static function type_offcanvas( $s ) {
		echo '<button type="button" class="spae-cta__btn spae-oc-open">' . esc_html( $s['button_text'] ) . '</button>';
		echo '<div class="spae-oc" hidden><button type="button" class="spae-oc-close">×</button>' . self::heading( $s ) . '<p>' . esc_html( $s['text'] ) . '</p></div>'; // phpcs:ignore
	}

	private static function type_faq_schema( $s ) {
		$items = self::items( $s );
		echo '<div class="spae-faq">';
		$schema = array();
		foreach ( $items as $item ) {
			echo '<div class="spae-faq__item"><button type="button" class="spae-faq__q" aria-expanded="false">' . esc_html( $item['item_title'] ) . '</button><div class="spae-faq__a" hidden>' . wp_kses_post( wpautop( $item['item_text'] ) ) . '</div></div>';
			$schema[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $item['item_title'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $item['item_text'] ),
				),
			);
		}
		echo '</div>';
		if ( $schema ) {
			$json = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $schema,
			);
			echo '<script type="application/ld+json">' . wp_json_encode( $json ) . '</script>';
		}
	}

	private static function type_protected( $s ) {
		if ( is_user_logged_in() ) {
			echo wp_kses_post( wpautop( $s['text'] ) );
			return;
		}
		echo '<p>' . esc_html( $s['title'] ? $s['title'] : __( 'Log in to view this note.', 'speedpress-addons' ) ) . '</p>';
	}

	private static function type_lang( $s ) {
		echo '<span class="spae-pill">' . esc_html( $s['title'] ? $s['title'] : determine_locale() ) . '</span>';
	}

	private static function type_social( $s ) {
		echo '<div class="spae-soc">';
		foreach ( self::items( $s ) as $item ) {
			$open = self::link_open( $item['item_url'] );
			if ( $open ) {
				echo $open . esc_html( $item['item_title'] ) . '</a>'; // phpcs:ignore
			}
		}
		echo '</div>';
	}

	private static function type_share( $s ) {
		$url   = rawurlencode( get_permalink() ? get_permalink() : home_url( '/' ) );
		$title = rawurlencode( wp_get_document_title() );
		echo '<div class="spae-soc">';
		echo '<a target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '">X</a>'; // phpcs:ignore
		echo '<a target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '">Facebook</a>'; // phpcs:ignore
		echo '<a target="_blank" rel="noopener" href="https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '">LinkedIn</a>'; // phpcs:ignore
		echo '</div>';
	}

	private static function type_authors( $s ) {
		$users = get_users( array( 'who' => 'authors', 'number' => (int) $s['post_count'] ) );
		echo '<div class="spae-grid">';
		foreach ( $users as $user ) {
			echo '<div class="spae-card">' . get_avatar( $user->ID, 72 ) . '<strong>' . esc_html( $user->display_name ) . '</strong></div>';
		}
		echo '</div>';
	}

	private static function type_tags( $s ) {
		wp_tag_cloud( array( 'smallest' => 12, 'largest' => 22 ) );
	}

	private static function type_sitemap( $s ) {
		echo '<ul class="spae-ul">';
		$pages = get_pages( array( 'number' => (int) $s['post_count'] ) );
		foreach ( $pages as $page ) {
			echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $page->post_title ) . '</a></li>';
		}
		echo '</ul>';
	}

	private static function type_event( $s ) {
		echo '<div class="spae-event"><strong>' . esc_html( $s['title'] ) . '</strong><p>' . esc_html( $s['datetime'] ) . '</p><p>' . esc_html( $s['text'] ) . '</p></div>';
	}

	private static function type_audio( $s ) {
		if ( ! empty( $s['url'] ) ) {
			echo wp_audio_shortcode( array( 'src' => $s['url'] ) );
			return;
		}
		echo '<p>' . esc_html__( 'Add an audio file URL.', 'speedpress-addons' ) . '</p>';
	}

	private static function type_motion( $s ) {
		echo '<div class="spae-motion">' . esc_html( $s['title'] ) . '</div>';
	}
}
