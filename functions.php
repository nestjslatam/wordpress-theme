<?php
/**
 * NestJS Latam — tema hijo de GeneratePress.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * La versión sale de la cabecera de style.css, no de una constante escrita a
 * mano: es lo único que se sube al publicar, y duplicarla garantiza que un
 * día diverjan.
 *
 * Importa más de lo que parece. Este valor es el `?ver=` de cada asset, y por
 * tanto lo que invalida la caché del navegador. Si se queda atrás, quien ya
 * visitó el sitio sigue viendo los estilos viejos durante `max-age` — una
 * semana, aquí — sin ninguna forma de saber por qué.
 */
define( 'NESTJSLATAM_VERSION', wp_get_theme()->get( 'Version' ) ?: '1.0.0' );

/**
 * Encola la hoja del padre y luego las del hijo.
 *
 * GeneratePress registra su estilo con el handle 'generate-style'. Declararlo
 * como dependencia es lo que garantiza el orden: si el hijo cargara primero,
 * el padre lo sobrescribiría y nada de esto se vería.
 */
function nestjslatam_enqueue_styles() {
	wp_enqueue_style(
		'generate-style',
		get_template_directory_uri() . '/style.css',
		array(),
		NESTJSLATAM_VERSION
	);

	// El orden importa: tokens define las variables que usan los demás.
	$sheets = array( 'tokens', 'base', 'components', 'blog', 'home' );

	foreach ( $sheets as $sheet ) {
		wp_enqueue_style(
			"nestjslatam-{$sheet}",
			get_stylesheet_directory_uri() . "/assets/css/{$sheet}.css",
			array( 'generate-style' ),
			NESTJSLATAM_VERSION
		);
	}

	wp_enqueue_style(
		'nestjslatam-style',
		get_stylesheet_uri(),
		array( 'generate-style' ),
		NESTJSLATAM_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'nestjslatam_enqueue_styles', 20 );

/**
 * Fuentes desde Google Fonts, con preconnect para que no bloqueen el render.
 */
function nestjslatam_enqueue_fonts() {
	wp_enqueue_style(
		'nestjslatam-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'nestjslatam_enqueue_fonts', 5 );

function nestjslatam_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => '' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'nestjslatam_preconnect', 10, 2 );

/**
 * Copiar al portapapeles en los bloques de código.
 *
 * Se encola sólo donde hay un <pre>, para no cargar JS en páginas que no lo
 * necesitan. La comprobación es del contenido renderizado, no del tipo de
 * entrada, porque un bloque de código puede aparecer en cualquier sitio.
 */
function nestjslatam_enqueue_scripts() {
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_script(
		'nestjslatam-copy',
		get_stylesheet_directory_uri() . '/assets/js/copy-code.js',
		array(),
		NESTJSLATAM_VERSION,
		true
	);

	wp_localize_script(
		'nestjslatam-copy',
		'nestjslatamI18n',
		array(
			'copy'   => __( 'Copiar', 'nestjslatam' ),
			'copied' => __( 'Copiado', 'nestjslatam' ),
			'failed' => __( 'No se pudo copiar', 'nestjslatam' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'nestjslatam_enqueue_scripts' );

require_once get_stylesheet_directory() . '/inc/header.php';
require_once get_stylesheet_directory() . '/inc/patterns.php';
require_once get_stylesheet_directory() . '/inc/setup.php';
