<?php
/**
 * Respaldo de cabecera.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pinta el nombre del sitio cuando GeneratePress no pinta nada.
 *
 * Se da cuando el Customizer tiene desactivados el logo Y el título: la
 * cabecera queda de 182 bytes, visualmente vacía, y no hay ninguna pista de
 * por qué. Este respaldo se desactiva solo en cuanto se active cualquiera de
 * los dos, así que no compite con la elección del usuario — sólo evita el
 * estado en el que no hay elección ninguna.
 */
function nestjslatam_header_fallback() {
	$tiene_logo   = function_exists( 'generate_has_logo' ) ? generate_has_logo() : has_custom_logo();
	$tiene_titulo = function_exists( 'generate_get_option' )
		? ! generate_get_option( 'hide_title' )
		: true;

	if ( $tiene_logo || $tiene_titulo ) {
		return;
	}

	printf(
		'<div class="nl-brand"><a class="nl-brand__link" href="%1$s" rel="home">'
			. '<span class="nl-brand__name">%2$s</span>'
			. '<span class="nl-brand__tag">%3$s</span>'
			. '</a></div>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) ),
		esc_html( get_bloginfo( 'description' ) )
	);
}
add_action( 'generate_site_branding', 'nestjslatam_header_fallback' );
add_action( 'generate_before_header_content', 'nestjslatam_header_fallback' );
