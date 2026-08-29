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
<section class="nl-hero" style="padding:4rem 1.5rem">
  <span class="nl-hero__eyebrow">Tu cuenta</span>
  <h1 class="nl-hero__title" style="font-size:clamp(1.8rem,4vw,2.5rem)">Entrar en <em>NestJS Latam</em></h1>
  <p class="nl-hero__lead">Con una cuenta puedes comentar los artículos y seguir las discusiones. Para colaborar en el código no hace falta: los repositorios están abiertos.</p>
</section>
<?php echo do_shortcode( '[nl_acceso]' ); ?>
<div class="nl-note">
  <p class="nl-note__title">¿Prefieres colaborar sin cuenta?</p>
  <p>Todo el código y la documentación están en <a href="https://github.com/nestjslatam">GitHub</a>, y cada página de la documentación tiene un enlace «Editar en GitHub» al final. No hace falta registrarse aquí para eso.</p>
</div>
