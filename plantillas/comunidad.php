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
<section class="nl-hero" style="padding:6rem 1.5rem 4rem">
  <span class="nl-hero__eyebrow">Comunidad</span>
  <h1 class="nl-hero__title">Aquí cabe<br><em>todo el mundo</em></h1>
  <p class="nl-hero__lead">No hace falta ser experto ni pedir permiso. Hay sitio para quien escribe, para quien corrige una errata y para quien pregunta lo que nadie se atreve a preguntar.</p>
  <div class="nl-hero__actions">
    <a class="nl-btn" href="https://github.com/nestjslatam">Explorar los repositorios</a>
    <a class="nl-btn nl-btn--ghost" href="/contact/">Escríbenos</a>
  </div>
</section>

<div class="nl-stats">
  <div class="nl-stat"><div class="nl-stat__value">4</div><div class="nl-stat__label">paquetes en npm</div></div>
  <div class="nl-stat"><div class="nl-stat__value">1111</div><div class="nl-stat__label">pruebas en verde</div></div>
  <div class="nl-stat"><div class="nl-stat__value">98,7%</div><div class="nl-stat__label">cobertura medida</div></div>
  <div class="nl-stat"><div class="nl-stat__value">MIT</div><div class="nl-stat__label">sin letra pequeña</div></div>
</div>
<section class="nl-section">
  <div class="nl-section__head">
    <h2 class="nl-section__title">Empieza por donde quieras</h2>
    <p class="nl-section__lead">De menos a más esfuerzo. Las seis cuentan igual.</p>
  </div>

  <div class="nl-ways">
    <div class="nl-way">
      <span class="nl-way__num">01</span>
      <p class="nl-way__title">Pregunta</p>
      <p class="nl-way__body">Si algo no se entiende, casi siempre es culpa de cómo está explicado. Cada pregunta mejora la documentación para el siguiente.</p>
      <a class="nl-way__cta" href="https://github.com/nestjslatam/ddd/discussions">Abrir una discusión</a>
    </div>

    <div class="nl-way">
      <span class="nl-way__num">02</span>
      <p class="nl-way__title">Corrige una página</p>
      <p class="nl-way__body">Cada página de la documentación tiene un enlace «Editar en GitHub» al final. Una errata arreglada es una contribución.</p>
      <a class="nl-way__cta" href="https://docs.nestjslatam.dev">Ir a la documentación</a>
    </div>

    <div class="nl-way">
      <span class="nl-way__num">03</span>
      <p class="nl-way__title">Reporta lo que se rompe</p>
      <p class="nl-way__body">Un reporte que documenta con precisión algo roto vale tanto como el arreglo. Adjunta la versión exacta y cómo reproducirlo.</p>
      <a class="nl-way__cta" href="https://github.com/nestjslatam/ddd/issues/new">Abrir un issue</a>
    </div>

    <div class="nl-way">
      <span class="nl-way__num">04</span>
      <p class="nl-way__title">Escribe un artículo</p>
      <p class="nl-way__body">¿Resolviste algo que te costó una tarde? Eso es un artículo. Lo publicamos con tu firma y tu enlace.</p>
      <a class="nl-way__cta" href="/contact/">Cuéntanos la idea</a>
    </div>

    <div class="nl-way">
      <span class="nl-way__num">05</span>
      <p class="nl-way__title">Manda un pull request</p>
      <p class="nl-way__body">Cada repositorio tiene una sección <strong>Contributing</strong> con tareas concretas: qué falta exactamente y cómo comprobar que lo arreglaste.</p>
      <a class="nl-way__cta" href="https://github.com/nestjslatam/ddd#colaborar">Ver qué falta</a>
    </div>

    <div class="nl-way">
      <span class="nl-way__num">06</span>
      <p class="nl-way__title">Cuéntalo</p>
      <p class="nl-way__body">Si algo de aquí te sirvió, decirlo en voz alta ayuda a que otros lo encuentren. La contribución más barata y de las más útiles.</p>
      <a class="nl-way__cta" href="https://github.com/nestjslatam">Dale una estrella</a>
    </div>
  </div>
</section>
<section class="nl-section">
  <div class="nl-section__head">
    <h2 class="nl-section__title">Antes de tu primer PR</h2>
    <p class="nl-section__lead">Esto es lo que ejecuta CI, y lo puedes correr tú en local. Si pasa, el listón está superado.</p>
  </div>

  <div class="nl-term" style="max-width:44rem;margin-inline:auto">
    <div class="nl-term__bar"><span class="nl-term__dot"></span><span class="nl-term__dot"></span><span class="nl-term__dot"></span><span class="nl-term__title">antes de abrir el PR</span></div>
<pre><code>npm run lint && npm run type-check && npm test</code></pre>
  </div>

  <p style="text-align:center;color:var(--nl-text-muted);font-size:var(--nl-text-sm);max-width:40rem;margin:1.5rem auto 0">
    Los commits siguen <a href="https://www.conventionalcommits.org/">Conventional Commits</a>.
    Cada repositorio tiene además su <a href="https://github.com/nestjslatam/ddd/security/policy">política de seguridad</a>
    con un canal privado — por favor no abras una vulnerabilidad como issue público.
  </p>
</section>
<section class="nl-section">
  <div class="nl-section__head">
    <h2 class="nl-section__title">Los repositorios</h2>
    <p class="nl-section__lead">Todo abierto, licencia MIT, el código a la vista.</p>
  </div>

  <div class="nl-repos">
    <a class="nl-repo" href="https://github.com/nestjslatam/ddd">
      <span class="nl-repo__icon">🧱</span>
      <span class="nl-repo__name">ddd</span>
      <span class="nl-repo__what">La librería principal y su aplicación de ejemplo</span>
      <span class="nl-repo__tag">TypeScript · MIT</span>
    </a>
    <a class="nl-repo" href="https://github.com/nestjslatam/ddd-cli">
      <span class="nl-repo__icon">🤖</span>
      <span class="nl-repo__name">ddd-cli</span>
      <span class="nl-repo__what">La herramienta de línea de comandos y el servidor MCP</span>
      <span class="nl-repo__tag">TypeScript · MIT</span>
    </a>
    <a class="nl-repo" href="https://github.com/nestjslatam/ddd-valueobjects">
      <span class="nl-repo__icon">💎</span>
      <span class="nl-repo__name">ddd-valueobjects</span>
      <span class="nl-repo__what">Los doce value objects ya hechos y probados</span>
      <span class="nl-repo__tag">TypeScript · MIT</span>
    </a>
    <a class="nl-repo" href="https://github.com/nestjslatam/ddd-event-sourcing">
      <span class="nl-repo__icon">📼</span>
      <span class="nl-repo__name">ddd-event-sourcing</span>
      <span class="nl-repo__what">Event sourcing sobre MongoDB</span>
      <span class="nl-repo__tag">TypeScript · MIT</span>
    </a>
    <a class="nl-repo" href="https://github.com/nestjslatam/docs">
      <span class="nl-repo__icon">📚</span>
      <span class="nl-repo__name">docs</span>
      <span class="nl-repo__what">Esta documentación. Se edita por PR como todo lo demás</span>
      <span class="nl-repo__tag">Markdown · MIT</span>
    </a>
    <a class="nl-repo" href="https://github.com/nestjslatam/wordpress-theme">
      <span class="nl-repo__icon">🎨</span>
      <span class="nl-repo__name">wordpress-theme</span>
      <span class="nl-repo__what">El diseño de este portal, versionado en git</span>
      <span class="nl-repo__tag">PHP · CSS · MIT</span>
    </a>
  </div>
</section>
<section class="nl-cta">
  <h2 class="nl-cta__title">Lo que valoramos por encima del código</h2>
  <p class="nl-cta__lead"><strong>Que las afirmaciones sean ciertas.</strong> Si un README dice que algo funciona, es porque alguien lo ejecutó. Si algo está roto, lo decimos antes de que lo descubras tú. Un reporte que documenta con precisión algo que no funciona vale tanto como el arreglo.</p>
  <div class="nl-hero__actions">
    <a class="nl-btn" href="https://github.com/nestjslatam">Empezar por aquí</a>
    <a class="nl-btn nl-btn--ghost" href="/guias/">Leer las guías</a>
  </div>
</section>
