<?php
/**
 * Contenido de la página. Vive en el tema y no en la base de datos:
 * el importador de WordPress escapa las comillas del HTML de los bloques
 * `wp:html`, y las clases dejaban de existir — la página salía sin formato.
 *
 * @package nestjslatam
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="nl-hero" style="padding:5rem 1.5rem">
  <span class="nl-hero__eyebrow">Documentación</span>
  <h1 class="nl-hero__title" style="font-size:clamp(2rem,5vw,3rem)">Todo lo que necesitas<br>para <em>empezar</em></h1>
  <p class="nl-hero__lead">La documentación técnica vive junto al código, en <a href="https://docs.nestjslatam.dev">docs.nestjslatam.dev</a>, para que no se quede atrás cuando la librería cambie.</p>
  <div class="nl-hero__actions">
    <a class="nl-btn" href="https://docs.nestjslatam.dev/guia/">Empezar la guía</a>
    <a class="nl-btn nl-btn--ghost" href="https://docs.nestjslatam.dev">Ver toda la documentación</a>
  </div>
</section>

<section class="nl-section">
  <div class="nl-section__head">
    <h2 class="nl-section__title">Por dónde empezar</h2>
    <p class="nl-section__lead">Si es tu primera vez, el orden de abajo es el que menos duele.</p>
  </div>

  <div class="nl-cards">
    <article class="nl-card">
      <span class="nl-pill">1</span>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/">Qué es esto</a></h3>
      <p class="nl-card__body">La idea que organiza toda la librería: la validación recolecta reglas rotas en vez de lanzar en la primera. Cinco minutos.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/">Leer</a>
    </article>

    <article class="nl-card">
      <span class="nl-pill">2</span>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/instalacion">Instalación</a></h3>
      <p class="nl-card__body">Instalar, clavar la versión y comprobar que funciona. Incluye por qué clavar la versión no es una manía.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/instalacion">Leer</a>
    </article>

    <article class="nl-card">
      <span class="nl-pill">3</span>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/primer-agregado">Tu primer agregado</a></h3>
      <p class="nl-card__body">De cero a un <code>Product</code> que valida sus propias reglas, con el código que corre en CI.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/primer-agregado">Leer</a>
    </article>

    <article class="nl-card">
      <span class="nl-pill">4</span>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/reglas-rotas">Reglas rotas</a></h3>
      <p class="nl-card__body">Los tres errores clásicos, con su síntoma. Dos de ellos no producen ningún error visible.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/reglas-rotas">Leer</a>
    </article>
  </div>
</section>

<section class="nl-section">
  <div class="nl-section__head">
    <h2 class="nl-section__title">Por tema</h2>
  </div>

  <div class="nl-cards">
    <article class="nl-card">
      <div class="nl-card__icon">🧱</div>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/agregados">El dominio</a></h3>
      <p class="nl-card__body">Agregados, value objects, reglas rotas y eventos de dominio.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/agregados">Ver</a>
    </article>
    <article class="nl-card">
      <div class="nl-card__icon">⚙️</div>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/cqrs">La aplicación</a></h3>
      <p class="nl-card__body">Comandos, consultas y cómo mapear los errores del dominio a 400, 422 y 409.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/cqrs">Ver</a>
    </article>
    <article class="nl-card">
      <div class="nl-card__icon">🤖</div>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/cli/">El CLI</a></h3>
      <p class="nl-card__body">Comandos, auditoría del idioma y el servidor MCP con sus siete herramientas.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/cli/">Ver</a>
    </article>
    <article class="nl-card">
      <div class="nl-card__icon">📖</div>
      <h3 class="nl-card__title"><a href="https://docs.nestjslatam.dev/guia/api">Referencia</a></h3>
      <p class="nl-card__body">Las piezas públicas, y qué cambió entre versiones.</p>
      <a class="nl-card__cta" href="https://docs.nestjslatam.dev/guia/api">Ver</a>
    </article>
  </div>
</section>

<div class="nl-note nl-note--warn">
  <p class="nl-note__title">Una advertencia honesta</p>
  <p>La API todavía se mueve y ha roto en más de una versión: <strong>clava una versión exacta</strong>. Cada repositorio dice en su README qué está probado y qué no, con números medidos y no prometidos.</p>
</div>
