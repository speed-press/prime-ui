<?php
namespace SpeedPress\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Layouts {

	public static function options( $type, $title ) {
		$sets = self::sets();
		$family = self::family( $type );
		$base = isset( $sets[ $family ] ) ? $sets[ $family ] : $sets['content'];
		$out = array();
		$i = 1;
		foreach ( $base as $label ) {
			$out[ 'l' . $i ] = $i . '. ' . $title . ' — ' . $label;
			$i++;
		}
		return $out;
	}

	public static function family( $type ) {
		$map = array(
			'icon_box' => 'cards', 'overlay_card' => 'cards', 'promo' => 'cards', 'offer' => 'cards', 'stat_card' => 'cards', 'feature_list' => 'cards',
			'quote' => 'proof', 'team' => 'proof', 'team_grid' => 'proof', 'rating_badge' => 'proof', 't_slider' => 'proof',
			'image_grid' => 'media', 'image_caption' => 'media', 'logo_grid' => 'media', 'video_popup' => 'media',
			'check_list' => 'list', 'notice' => 'list', 'alert' => 'list', 'badge' => 'list',
			'table' => 'data', 'specs' => 'data', 'progress' => 'data', 'skills' => 'data', 'circle' => 'data', 'compare' => 'data',
			'menu' => 'nav', 'search' => 'nav', 'creative_button' => 'nav',
			'posts_grid' => 'posts', 'posts_list' => 'posts', 'cats' => 'posts',
			'dual_heading' => 'heading', 'gradient_heading' => 'heading',
			'hours' => 'info', 'map' => 'info', 'whatsapp' => 'info', 'login' => 'info', 'shortcode' => 'info', 'html_embed' => 'info',
		);
		return isset( $map[ $type ] ) ? $map[ $type ] : 'content';
	}

	private static function sets() {
		return array(
			'cards' => array( 'Icon on top', 'Icon left row', 'Dark feature tile', 'Gradient feature', 'Outline hover card', 'Numbered feature', 'Wide split feature', 'Centered circle icon', 'Bento lead card', 'Minimal line card' ),
			'proof' => array( 'Quote card', 'Avatar left', 'Dark testimonial', 'Star band', 'Centered quote', 'Logo plus quote', 'Grid of people', 'Overlay portrait', 'Pill rating', 'Split quote and photo' ),
			'media' => array( 'Masonry tiles', 'Caption below', 'Overlay title', 'Round logos', 'Filmstrip', 'Lightbox tile', 'Bordered frames', 'Full-bleed pair', 'Polaroid stack', 'Soft gallery' ),
			'list' => array( 'Checks in a card', 'Two-column checks', 'Alert bar', 'Inline badges', 'Numbered list', 'Icon rows', 'Soft notice', 'Dark notice', 'Pill tags', 'Divided rows' ),
			'data' => array( 'Spec table', 'Compare columns', 'Progress bars', 'Skill meters', 'Circle stat', 'Striped rows', 'Dark data panel', 'Compact specs', 'Highlighted row', 'Card per metric' ),
			'nav' => array( 'Text button', 'Pill button', 'Outline button', 'Icon left button', 'Icon right button', 'Split menu', 'Search bar', 'Dark nav bar', 'Gradient button', 'Ghost button' ),
			'posts' => array( 'Post cards', 'Post list', 'Magazine lead', 'Overlay posts', 'Category chips', 'Date rail', 'Three-column news', 'Compact titles', 'Dark post cards', 'Image left list' ),
			'heading' => array( 'Split tone', 'Gradient word', 'Eyebrow plus title', 'Center headline', 'Underline accent', 'Dark headline band', 'Left rule', 'Stacked kicker', 'Outline title', 'Wide display' ),
			'info' => array( 'Info card', 'Map panel', 'Hours table', 'Contact row', 'Chat pill', 'Login card', 'Embed frame', 'Dark info', 'Split contact', 'Notice strip' ),
			'content' => array( 'Soft block', 'Dark block', 'Split copy', 'Centered block', 'Bordered block', 'Banner', 'Sidebar note', 'Stat strip', 'Quote strip', 'Compact bar' ),
		);
	}
}
