<?php

/**
* Authored by Denver
* Used to write cleaner styles and scripts.
* Allows prepending and appending of scripts or other HTML before and after scripts.
*/

function Denver_styleTagConstructor($tag, $handle) {
	global $wp_styles;

	$handle = $wp_styles->registered[$handle]->handle;
	$media = ( $wp_styles->registered[$handle]->args !== 'all' && $wp_styles->registered[$handle]->args !== '' ? ' media="'. $wp_styles->registered[$handle]->args .'"' : '' );
	$href = $wp_styles->registered[$handle]->src . ( $wp_styles->registered[$handle]->ver ? '?ver=' . $wp_styles->registered[$handle]->ver : '');
	$title = isset($wp_styles->registered[$handle]->extra['title']) ? ' title="' . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . '"' : '';

	if ( !preg_match( '|^(https?:)?//|', $href ) ){
		$href = get_site_url( NULL, $href );
	}

	$tag = sprintf( '<link%4$s rel="stylesheet" href="%1$s"%3$s id="Style-%2$s" />', $href, $handle, $media, $title ). "\n";
	return $tag;
}
add_filter( 'style_loader_tag', 'Denver_styleTagConstructor', 10, 2 );



function Denver_scriptTagConstructor( $tag, $handle, $src ) {
	global $wp_scripts;

	$handle = $wp_scripts->registered[$handle]->handle;
	$src = $wp_scripts->registered[$handle]->src . ( $wp_scripts->registered[$handle]->ver ? '?ver=' . $wp_scripts->registered[$handle]->ver : '');

	if ( !preg_match( '|^(https?:)?//|', $src ) ){
		$src = get_site_url( NULL, $src );
	}

	do_action( 'script-'. $handle .'-before' );
	echo sprintf( '<script src="%1$s" id="Script-%2$s"></script>', $src, $handle ). "\n";
	do_action( 'script-'. $handle .'-after' );

	return '';
}
add_filter( 'script_loader_tag', 'Denver_scriptTagConstructor', 5, 3 );
