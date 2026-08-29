<?php
/**
 * Patrones de bloques.
 *
 * Son lo que convierte el CSS de este tema en algo usable sin escribir HTML:
 * en el editor aparecen bajo «NestJS Latam» y se insertan ya montados.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;

function nestjslatam_register_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'nestjslatam',
		array( 'label' => __( 'NestJS Latam', 'nestjslatam' ) )
	);

	register_block_pattern(
		'nestjslatam/hero',
		array(
			'title'      => __( 'Portada con titular', 'nestjslatam' ),
			'categories' => array( 'nestjslatam' ),
			'content'    => '<!-- wp:html -->
<section class="nl-hero">
  <span class="nl-hero__eyebrow">Comunidad hispana</span>
  <h1 class="nl-hero__title">Domain-Driven Design para <em>NestJS</em></h1>
  <p class="nl-hero__lead">Librerías, artículos y herramientas en español. Todo en npm, todo MIT, todo con el código a la vista.</p>
  <div class="nl-hero__actions">
    <a class="nl-btn" href="https://docs.nestjslatam.dev">Leer la documentación</a>
    <a class="nl-btn nl-btn--ghost" href="https://github.com/nestjslatam">Ver en GitHub</a>
  </div>
</section>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'nestjslatam/cards',
		array(
			'title'      => __( 'Rejilla de tarjetas', 'nestjslatam' ),
			'categories' => array( 'nestjslatam' ),
			'content'    => '<!-- wp:html -->
<div class="nl-cards">
  <article class="nl-card">
    <div class="nl-card__icon">🧱</div>
    <h3 class="nl-card__title"><a href="https://github.com/nestjslatam/ddd">ddd-lib</a></h3>
    <p class="nl-card__body">Agregados que acumulan sus propias reglas rotas, value objects que se validan solos y seguimiento de estado.</p>
    <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/">Leer la guía</a>
  </article>
  <article class="nl-card">
    <div class="nl-card__icon">🤖</div>
    <h3 class="nl-card__title"><a href="https://github.com/nestjslatam/ddd-cli">ddd-cli</a></h3>
    <p class="nl-card__body">Entiende, genera y audita tu dominio. Corre como servidor MCP sin necesitar clave de API.</p>
    <a class="nl-card__cta" href="https://docs.nestjslatam.dev/cli/">Ver comandos</a>
  </article>
  <article class="nl-card">
    <div class="nl-card__icon">💎</div>
    <h3 class="nl-card__title"><a href="https://github.com/nestjslatam/ddd-valueobjects">ddd-valueobjects</a></h3>
    <p class="nl-card__body">Doce value objects ya hechos: email, dinero, teléfono, documentos de identidad, fechas.</p>
    <a class="nl-card__cta" href="https://docs.nestjslatam.dev/valueobjects/">Ver el catálogo</a>
  </article>
  <article class="nl-card">
    <div class="nl-card__icon">📼</div>
    <h3 class="nl-card__title"><a href="https://github.com/nestjslatam/ddd-event-sourcing">ddd-es-lib</a></h3>
    <p class="nl-card__body">Event sourcing sobre MongoDB: event store, snapshots, upcasting, sagas y vistas materializadas.</p>
    <a class="nl-card__cta" href="https://docs.nestjslatam.dev/event-sourcing/">Empezar</a>
  </article>
</div>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'nestjslatam/note',
		array(
			'title'      => __( 'Aviso', 'nestjslatam' ),
			'categories' => array( 'nestjslatam' ),
			'content'    => '<!-- wp:html -->
<div class="nl-note">
  <p class="nl-note__title">Nota</p>
  <p>El texto del aviso va aquí.</p>
</div>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'nestjslatam/warning',
		array(
			'title'      => __( 'Advertencia', 'nestjslatam' ),
			'categories' => array( 'nestjslatam' ),
			'content'    => '<!-- wp:html -->
<div class="nl-note nl-note--warn">
  <p class="nl-note__title">Ojo</p>
  <p>Lo que conviene saber antes de seguir.</p>
</div>
<!-- /wp:html -->',
		)
	);
}
add_action( 'init', 'nestjslatam_register_patterns' );
