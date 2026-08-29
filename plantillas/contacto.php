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
<section class="nl-hero" style="padding:4rem 1.5rem 2rem">
  <span class="nl-hero__eyebrow">Contacto</span>
  <h1 class="nl-hero__title" style="font-size:clamp(1.8rem,4vw,2.5rem)">Escríbenos</h1>
  <p class="nl-hero__lead">Dudas, ideas para un artículo, o algo que no funciona. Respondemos a todo.</p>
</section>

<div class="nl-cards" style="margin-bottom:0">
  <article class="nl-card">
    <div class="nl-card__icon">🐛</div>
    <h3 class="nl-card__title">¿Es un fallo?</h3>
    <p class="nl-card__body">Un issue en GitHub llega antes y queda público para quien tenga el mismo problema. Adjunta la versión exacta y cómo reproducirlo.</p>
    <a class="nl-card__cta" href="https://github.com/nestjslatam/ddd/issues/new">Abrir un issue</a>
  </article>
  <article class="nl-card">
    <div class="nl-card__icon">🔒</div>
    <h3 class="nl-card__title">¿Es una vulnerabilidad?</h3>
    <p class="nl-card__body">No la abras como issue público. Cada repositorio tiene un canal privado para reportarlas.</p>
    <a class="nl-card__cta" href="https://github.com/nestjslatam/ddd/security/policy">Política de seguridad</a>
  </article>
  <article class="nl-card">
    <div class="nl-card__icon">✍️</div>
    <h3 class="nl-card__title">¿Quieres escribir?</h3>
    <p class="nl-card__body">Cuéntanos la idea por aquí abajo. Lo publicamos con tu firma y tu enlace.</p>
    <a class="nl-card__cta" href="#formulario">Al formulario</a>
  </article>
</div>
<h2 id="formulario" class="nl-section__title" style="text-align:left;border:0;padding:0;margin-top:3rem">Para todo lo demás</h2>
<?php echo do_shortcode( '[nl_contacto]' ); ?>
