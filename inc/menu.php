<?php
/**
 * Menú de respaldo.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Los destinos del sitio, cuando no hay menú asignado.
 *
 * Un sitio recién instalado no tiene menú en la ubicación principal, y
 * GeneratePress responde listando todas las páginas por orden alfabético — o
 * nada. Ninguna de las dos cosas ayuda a alguien que llega buscando la guía.
 *
 * Este respaldo se aparta solo en cuanto se asigne un menú de verdad:
 * `has_nav_menu()` decide, así que el día que se configure en Apariencia ->
 * Menús, esto deja de ejecutarse sin que haya que tocar nada.
 */
/**
 * Los destinos del sitio, en un solo sitio.
 *
 * Antes había dos listas escritas a mano —ésta y la del generador del XML— y
 * se separaron: la guía extendida del CLI en GitHub y la sección CLI entera
 * desaparecieron del menú sin que nada avisara. Una sola fuente evita que
 * vuelva a pasar dentro del tema; `contenido/generar-wxr.py` se compara con
 * ésta en CI.
 */
function nestjslatam_destinos() {
	return array(
		// Un solo destino de documentación. Antes había «Guías» y
		// «Documentación» como dos entradas de primer nivel, y desde fuera son
		// lo mismo: el visitante no tiene forma de saber cuál le toca.
		array(
			'texto' => __( 'Documentación', 'nestjslatam' ),
			'url'   => home_url( '/documentacion/' ),
			'hijos' => array(
				array( 'texto' => __( 'Guía de ddd-lib', 'nestjslatam' ),    'url' => home_url( '/guia-ddd-lib/' ) ),
				array( 'texto' => __( 'Guía del CLI', 'nestjslatam' ),       'url' => home_url( '/guia-cli/' ) ),
				array( 'texto' => __( 'Referencia completa', 'nestjslatam' ), 'url' => 'https://docs.nestjslatam.dev' ),
				array( 'texto' => __( 'Value objects', 'nestjslatam' ),      'url' => 'https://docs.nestjslatam.dev/valueobjects/' ),
				array( 'texto' => __( 'Event sourcing', 'nestjslatam' ),     'url' => 'https://docs.nestjslatam.dev/event-sourcing/' ),
				array( 'texto' => __( 'Referencia de API', 'nestjslatam' ),  'url' => 'https://docs.nestjslatam.dev/guia/api' ),
				array( 'texto' => __( 'Guía extendida del CLI', 'nestjslatam' ), 'url' => 'https://github.com/nestjslatam/ddd-cli/blob/main/docs/GUIDE.md' ),
				array( 'texto' => __( 'Servidor MCP', 'nestjslatam' ),       'url' => 'https://docs.nestjslatam.dev/cli/mcp' ),
			),
		),
		array(
			'texto' => __( 'Tutoriales', 'nestjslatam' ),
			'url'   => home_url( '/guias/' ),
			'hijos' => array(
				array( 'texto' => __( 'Tu primer agregado', 'nestjslatam' ),     'url' => home_url( '/como-montar-tu-primer-agregado-paso-a-paso/' ) ),
				array( 'texto' => __( 'Probar un agregado', 'nestjslatam' ),     'url' => home_url( '/como-probar-un-agregado/' ) ),
				array( 'texto' => __( 'Modelar dinero', 'nestjslatam' ),         'url' => home_url( '/como-modelar-dinero-sin-equivocarte/' ) ),
				array( 'texto' => __( 'Migrar a la 4.0.0', 'nestjslatam' ),      'url' => home_url( '/como-migrar-de-ddd-lib-3-a-4/' ) ),
				array( 'texto' => __( 'Conectar el CLI a tu agente', 'nestjslatam' ), 'url' => home_url( '/como-conectar-el-cli-a-tu-agente-por-mcp/' ) ),
			),
		),
		array( 'texto' => __( 'Blog', 'nestjslatam' ),      'url' => home_url( '/blog/' ) ),
		array( 'texto' => __( 'Comunidad', 'nestjslatam' ), 'url' => home_url( '/comunidad/' ) ),
		array(
			'texto' => __( 'Repositorios', 'nestjslatam' ),
			'url'   => 'https://github.com/nestjslatam',
			'hijos' => array(
				array( 'texto' => 'ddd-lib',            'url' => 'https://github.com/nestjslatam/ddd' ),
				array( 'texto' => 'ddd-cli',            'url' => 'https://github.com/nestjslatam/ddd-cli' ),
				array( 'texto' => 'ddd-valueobjects',   'url' => 'https://github.com/nestjslatam/ddd-valueobjects' ),
				array( 'texto' => 'ddd-event-sourcing', 'url' => 'https://github.com/nestjslatam/ddd-event-sourcing' ),
				array( 'texto' => __( 'Documentación', 'nestjslatam' ), 'url' => 'https://github.com/nestjslatam/docs' ),
			),
		),
	);
}

function nestjslatam_menu_fallback() {
	$destinos = nestjslatam_destinos();

	echo '<ul id="menu-respaldo" class="menu sf-menu">';

	foreach ( $destinos as $d ) {
		$tiene = ! empty( $d['hijos'] );
		printf(
			'<li class="menu-item%1$s"><a href="%2$s">%3$s</a>',
			$tiene ? ' menu-item-has-children' : '',
			esc_url( $d['url'] ),
			esc_html( $d['texto'] )
		);

		if ( $tiene ) {
			echo '<ul class="sub-menu">';
			foreach ( $d['hijos'] as $h ) {
				printf(
					'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
					esc_url( $h['url'] ),
					esc_html( $h['texto'] )
				);
			}
			echo '</ul>';
		}

		echo '</li>';
	}

	echo '</ul>';
}

/**
 * Engancha el respaldo sólo cuando no hay menú asignado a la ubicación.
 */
function nestjslatam_nav_args( $args ) {
	if ( isset( $args['theme_location'] ) && 'primary' === $args['theme_location']
		&& ! has_nav_menu( 'primary' ) ) {
		$args['fallback_cb'] = 'nestjslatam_menu_fallback';
	}

	return $args;
}
add_filter( 'wp_nav_menu_args', 'nestjslatam_nav_args' );

/**
 * Completa el menú existente con los destinos que le falten.
 *
 * El respaldo de arriba sólo entra cuando NO hay menú asignado, y un sitio con
 * un menú de dos entradas heredado —«Acerca De», «Contacto»— no es ese caso:
 * tiene menú, y le faltan las guías. Sin esto, el visitante no tiene desde
 * dónde llegar a la documentación por mucho que las páginas existan.
 *
 * Cada destino se añade sólo si su URL no está ya en el menú, así que en
 * cuanto se configure el menú completo en Apariencia -> Menús esto deja de
 * añadir nada por sí solo. No hay que desactivarlo después.
 */
function nestjslatam_completar_menu( $items, $args ) {
	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	$esenciales = nestjslatam_destinos();

	$añadidos = '';

	foreach ( $esenciales as $d ) {
		// La comparación es sobre la URL, no sobre el texto: alguien puede
		// haber llamado «Docs» a la entrada de documentación.
		if ( false !== strpos( $items, 'href="' . esc_url( $d['url'] ) . '"' ) ) {
			continue;
		}

		$tiene = ! empty( $d['hijos'] );

		$añadidos .= sprintf(
			'<li class="menu-item%1$s nl-menu-auto"><a href="%2$s">%3$s</a>',
			$tiene ? ' menu-item-has-children' : '',
			esc_url( $d['url'] ),
			esc_html( $d['texto'] )
		);

		if ( $tiene ) {
			$añadidos .= '<ul class="sub-menu">';
			foreach ( $d['hijos'] as $h ) {
				$añadidos .= sprintf(
					'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
					esc_url( $h['url'] ),
					esc_html( $h['texto'] )
				);
			}
			$añadidos .= '</ul>';
		}

		$añadidos .= '</li>';
	}

	return $items . $añadidos;
}
add_filter( 'wp_nav_menu_items', 'nestjslatam_completar_menu', 10, 2 );
