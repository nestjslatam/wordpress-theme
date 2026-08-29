<?php
/**
 * Ajustes del tema y limpieza de la cabecera.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Soportes del tema. GeneratePress declara la mayoría; aquí sólo lo que
 * añadimos o queremos garantizar.
 */
function nestjslatam_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/tokens.css' );
	add_editor_style( 'assets/css/base.css' );

	load_child_theme_textdomain(
		'nestjslatam',
		get_stylesheet_directory() . '/languages'
	);
}
add_action( 'after_setup_theme', 'nestjslatam_setup' );

/**
 * Paleta del editor, tomada de los mismos tokens que el CSS.
 *
 * Sin esto, quien escriba una entrada elige colores de la paleta por defecto
 * de WordPress y el resultado no se parece al resto del sitio.
 */
function nestjslatam_editor_palette() {
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Azul NestJS Latam', 'nestjslatam' ),
				'slug'  => 'nl-blue',
				'color' => '#1e73be',
			),
			array(
				'name'  => __( 'Azul oscuro', 'nestjslatam' ),
				'slug'  => 'nl-blue-dark',
				'color' => '#14548c',
			),
			array(
				'name'  => __( 'Verde', 'nestjslatam' ),
				'slug'  => 'nl-green',
				'color' => '#00d084',
			),
			array(
				'name'  => __( 'Texto', 'nestjslatam' ),
				'slug'  => 'nl-text',
				'color' => '#16212e',
			),
			array(
				'name'  => __( 'Texto suave', 'nestjslatam' ),
				'slug'  => 'nl-text-muted',
				'color' => '#5b6b7c',
			),
			array(
				'name'  => __( 'Fondo sutil', 'nestjslatam' ),
				'slug'  => 'nl-bg-subtle',
				'color' => '#f7f9fb',
			),
		)
	);

	add_theme_support( 'disable-custom-colors' );
}
add_action( 'after_setup_theme', 'nestjslatam_editor_palette' );

/**
 * Quita de la cabecera lo que anuncia la instalación sin dar nada a cambio.
 *
 * `wp_generator` publica la versión exacta de WordPress, que es información
 * gratuita para quien busque instalaciones sin actualizar.
 */
function nestjslatam_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'nestjslatam_clean_head' );

/**
 * Deja el `?ver=` de WordPress fuera de los assets del núcleo, por el mismo
 * motivo: delata la versión en cada petición.
 */
function nestjslatam_strip_core_version( $src ) {
	if ( $src && strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) !== false ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'nestjslatam_strip_core_version', 9999 );
add_filter( 'script_loader_src', 'nestjslatam_strip_core_version', 9999 );

/**
 * Los enlaces externos del contenido se abren en otra pestaña y sin dejar
 * que la página destino toque `window.opener`.
 */
function nestjslatam_external_links( $content ) {
	if ( is_admin() || empty( $content ) ) {
		return $content;
	}

	$host = wp_parse_url( home_url(), PHP_URL_HOST );

	return preg_replace_callback(
		'/<a\s([^>]*href=["\']https?:\/\/([^"\'\/]+)[^>]*)>/i',
		function ( $matches ) use ( $host ) {
			if ( false !== strpos( $matches[2], $host ) ) {
				return $matches[0];
			}

			$attrs = $matches[1];

			if ( false === stripos( $attrs, 'target=' ) ) {
				$attrs .= ' target="_blank"';
			}
			if ( false === stripos( $attrs, 'rel=' ) ) {
				$attrs .= ' rel="noopener noreferrer"';
			}

			return '<a ' . $attrs . '>';
		},
		$content
	);
}
add_filter( 'the_content', 'nestjslatam_external_links', 20 );
