<?php
/**
 * Contenido de las páginas, servido desde el tema.
 *
 * El importador de WordPress escapa las comillas del HTML que va dentro de un
 * bloque `wp:html`: `class="nl-hero"` llega a la base de datos como
 * `class=\"nl-hero\"`, así que la clase real pasa a ser `\"nl-hero\"` y ninguna
 * regla de CSS casa. El resultado es una página de texto plano, y no hay nada
 * en el editor que lo delate.
 *
 * En lugar de pelear con el importador, el marcado vive en `plantillas/` y la
 * página de WordPress sólo lleva un atajo. Ventajas de paso: el contenido se
 * revisa por PR, el XML baja de 190 KB a menos de 20, y editar una página no
 * exige reimportar nada.
 *
 * Uso: [nl_pagina nombre="comunidad"]
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renderiza una plantilla de `plantillas/`.
 */
function nestjslatam_pagina_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'nombre' => '' ), $atts, 'nl_pagina' );

	// Sólo minúsculas, cifras y guiones: el nombre viene del contenido de una
	// página, y aunque hoy lo escribamos nosotros, un atajo es entrada de
	// usuario en cuanto alguien con permiso de edición lo toca.
	$nombre = preg_replace( '/[^a-z0-9-]/', '', strtolower( $atts['nombre'] ) );

	if ( '' === $nombre ) {
		return '';
	}

	$ruta = get_stylesheet_directory() . '/plantillas/' . $nombre . '.php';

	// realpath + comprobación de prefijo: sin esto, un nombre trabajado podría
	// salirse del directorio. El filtro de arriba ya lo impide, pero una sola
	// defensa contra el recorrido de rutas es una defensa de menos.
	$real = realpath( $ruta );
	$base = realpath( get_stylesheet_directory() . '/plantillas' );

	if ( ! $real || ! $base || 0 !== strpos( $real, $base ) || ! is_readable( $real ) ) {
		return '';
	}

	ob_start();
	include $real;

	return ob_get_clean();
}
add_shortcode( 'nl_pagina', 'nestjslatam_pagina_shortcode' );

/**
 * El atajo debe ejecutarse aunque el contenido llegue escapado.
 *
 * Si una página ya se importó con las comillas escapadas, `[nl_pagina
 * nombre=\"comunidad\"]` no lo reconoce el analizador de atajos de WordPress.
 * Se normaliza antes de que corra, y así las páginas ya importadas se
 * arreglan solas sin reimportar.
 */
function nestjslatam_desescapar_atajo( $contenido ) {
	if ( false === strpos( $contenido, 'nl_pagina' ) ) {
		return $contenido;
	}

	return preg_replace_callback(
		'/\[nl_pagina[^\]]*\]/',
		function ( $m ) {
			return stripslashes( $m[0] );
		},
		$contenido
	);
}
add_filter( 'the_content', 'nestjslatam_desescapar_atajo', 5 );

/**
 * Repara los atributos que el importador dejó escapados.
 *
 * El síntoma es que la clase real pasa a ser `\"nl-note\"` en lugar de
 * `nl-note`, así que ninguna regla de CSS casa y el contenido sale como texto
 * plano. No hay nada en el editor que lo delate: el bloque se ve bien.
 *
 * La sustitución se limita a la forma `atributo=\"valor\"`, que dentro de una
 * etiqueta HTML nunca es contenido legítimo. Una comilla escapada dentro de
 * un bloque de código —`it(\'...\')` y similares— no encaja con este patrón y
 * se queda como está, que es lo correcto.
 *
 * Esto arregla las entradas ya importadas sin tener que reimportar. Las
 * páginas ya no lo necesitan porque su marcado vive en plantillas/.
 */
function nestjslatam_reparar_atributos( $contenido ) {
	if ( false === strpos( $contenido, '=\\"' ) ) {
		return $contenido;
	}

	return preg_replace(
		'/([a-zA-Z-]+)=\\\\"([^"\\\\]*)\\\\"/',
		'$1="$2"',
		$contenido
	);
}
add_filter( 'the_content', 'nestjslatam_reparar_atributos', 6 );
