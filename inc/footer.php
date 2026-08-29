<?php
/**
 * Pie de página.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sustituye la línea de copyright de GeneratePress por el pie completo.
 *
 * Se usa el filtro `generate_copyright` en lugar de un hook de acción porque
 * así el pie del padre no se pinta además del nuestro: devolver contenido
 * aquí reemplaza el suyo en vez de sumarse.
 */
function nestjslatam_footer( $copyright ) {
	ob_start();
	$anio = gmdate( 'Y' );
	?>
	<div class="nl-footer">
		<div class="nl-footer__grid">

			<div class="nl-footer__brand">
				<a class="nl-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo-96.png' ); ?>"
					     width="44" height="44" alt="" loading="lazy" decoding="async">
					<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				</a>
				<p class="nl-footer__tag"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
				<p class="nl-footer__made">Hecho en Perú 🇵🇪</p>
			</div>

			<nav class="nl-footer__col" aria-label="Documentación">
				<h3 class="nl-footer__title">Documentación</h3>
				<ul>
					<li><a href="https://docs.nestjslatam.dev/guia/">Guía de ddd-lib</a></li>
					<li><a href="https://docs.nestjslatam.dev/cli/">CLI y servidor MCP</a></li>
					<li><a href="https://docs.nestjslatam.dev/valueobjects/">Value objects</a></li>
					<li><a href="https://docs.nestjslatam.dev/event-sourcing/">Event sourcing</a></li>
					<li><a href="https://docs.nestjslatam.dev/guia/api">Referencia de API</a></li>
				</ul>
			</nav>

			<nav class="nl-footer__col" aria-label="Comunidad">
				<h3 class="nl-footer__title">Comunidad</h3>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
					<li><a href="<?php echo esc_url( home_url( '/guias/' ) ); ?>">Guías y How-To</a></li>
					<li><a href="<?php echo esc_url( home_url( '/comunidad/' ) ); ?>">Cómo participar</a></li>
					<li><a href="https://github.com/nestjslatam">GitHub</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contacto</a></li>
				</ul>
			</nav>

			<div class="nl-footer__col nl-footer__powered">
				<h3 class="nl-footer__title">Impulsado por</h3>
				<a class="nl-powered" href="https://beyondnet.info/">
					<span class="nl-powered__name">BeyondNetCode</span>
					<span class="nl-powered__what">Ingeniería de software y gobierno de arquitectura</span>
				</a>
				<a class="nl-powered" href="https://github.com/beyondnetcode/evolith_arch32">
					<span class="nl-powered__name">Evolith&nbsp;Core</span>
					<span class="nl-powered__what">Arquitectura ejecutable: CLI, MCP y API que auditan un repositorio contra reglas, e informan de una regla no evaluable como fallo y no como aprobado</span>
				</a>
			</div>

		</div>

		<div class="nl-footer__nest">
			<div class="nl-nest">
				<span class="nl-nest__mark" aria-hidden="true">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none">
						<path d="M12 2.2 21 7.3v9.4L12 21.8 3 16.7V7.3L12 2.2Z"
						      stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
						<path d="M8.6 9.2v5.6M8.6 9.2l6.8 5.6M15.4 9.2v5.6"
						      stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
					</svg>
				</span>
				<p class="nl-nest__text">
					Construido sobre <a href="https://nestjs.com/"><strong>NestJS</strong></a>,
					creado por <a href="https://github.com/kamilmysliwiec">Kamil&nbsp;Myśliwiec</a>
					y publicado bajo licencia MIT.
					<a href="https://docs.nestjs.com/">Documentación oficial</a> ·
					<a href="https://github.com/nestjs/nest">Repositorio</a>
				</p>
			</div>

			<p class="nl-disclaimer">
				<strong>Proyecto de comunidad, no oficial.</strong>
				NestJS Latam no está afiliado, asociado, autorizado ni respaldado por NestJS
				ni por sus autores. «NestJS» y su logotipo pertenecen a sus respectivos
				titulares y se mencionan aquí únicamente para identificar la tecnología
				sobre la que trabajamos.
				<a href="<?php echo esc_url( home_url( '/aviso-legal/' ) ); ?>">Aviso legal completo</a>
			</p>
		</div>

		<div class="nl-footer__bar">
			<p class="nl-footer__legal">
				© <?php echo esc_html( $anio ); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
				· Contenido y código bajo licencia
				<a href="https://opensource.org/licenses/MIT" rel="license">MIT</a>
				· <a href="<?php echo esc_url( home_url( '/aviso-legal/' ) ); ?>">Aviso legal</a>
			</p>
			<p class="nl-footer__by">
				Powered by <a href="https://beyondnet.info/"><strong>BeyondNetCode</strong></a>
				· <a href="https://github.com/beyondnetcode/evolith_arch32">Evolith Core</a>
			</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_filter( 'generate_copyright', 'nestjslatam_footer' );
