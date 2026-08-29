# Tema de nestjslatam.dev

Tema **hijo de GeneratePress** para [nestjslatam.dev](https://nestjslatam.dev). El diseño del portal, versionado en git y desplegado como zip.

## Por qué un tema hijo

GeneratePress se actualiza desde WordPress. Si editaras sus ficheros, la siguiente actualización se llevaría por delante tu trabajo, en silencio y sin aviso.

Un tema hijo carga **después** del padre y sólo aporta lo que cambia: aquí, unos 18 KB de CSS, un poco de JavaScript y cuatro patrones de bloque. GeneratePress sigue actualizándose con normalidad.

## Instalar

```bash
./build.sh
```

Genera `dist/nestjslatam-1.0.0.zip`. Después, en el WordPress:

1. **Apariencia → Temas → Añadir nuevo → Subir tema**
2. Elige el zip y pulsa **Instalar ahora**
3. **Activar**

GeneratePress debe seguir instalado. No lo borres: el hijo lo necesita.

> El zip contiene una carpeta raíz `nestjslatam/`. Es obligatorio — un zip con los ficheros sueltos en la raíz lo rechaza WordPress sin decir por qué.

## Actualizar

Sube el zip nuevo por la misma vía y confirma la sustitución. Los ajustes del Customizer, los menús y las entradas no se tocan: viven en la base de datos, no en el tema.

## Qué trae

| | |
|---|---|
| **Tokens** | `assets/css/tokens.css` — paleta, tipografía, espaciado y sombras. Cambia la identidad desde aquí y se propaga sola |
| **Modo oscuro** | Sigue la preferencia del sistema; cada token se redefine, ninguno queda a medias |
| **Tipografía** | Inter para texto, JetBrains Mono para código, con `preconnect` para que no bloqueen el render |
| **Bloques de código** | Fondo oscuro, scroll horizontal propio y botón de **copiar** que aparece al pasar por encima |
| **Progreso de lectura** | Barra fina arriba en las entradas individuales |
| **Patrones** | Portada, rejilla de tarjetas y dos tipos de aviso, insertables desde el editor |
| **Paleta del editor** | Los mismos colores del CSS, para que una entrada nueva no se salga de la identidad |
| **Cabecera limpia** | Sin `wp_generator` ni `?ver=` del núcleo: no anuncian la versión instalada a quien busque instalaciones sin actualizar |
| **Enlaces externos** | `target="_blank"` con `rel="noopener noreferrer"` automáticos |

## Estructura

```
style.css          la cabecera que WordPress lee (los estilos NO van aquí)
functions.php      encolado, en orden y con el padre como dependencia
inc/setup.php      soportes, paleta del editor, limpieza de cabecera
inc/patterns.php   los cuatro patrones de bloque
assets/css/        tokens → base → components → blog, en ese orden
assets/js/         copiar código y barra de progreso
build.sh           empaqueta el zip
```

El orden de encolado no es cosmético: `tokens.css` define las variables que usan los otros tres, y `generate-style` se declara como dependencia para que el padre nunca cargue después del hijo.

## Desarrollo

No hace falta compilar nada: es CSS y JS planos.

```bash
# comprobar la sintaxis PHP sin instalar PHP
docker run --rm -v "$PWD":/app -w /app php:8.3-cli \
  sh -c 'for f in functions.php inc/*.php; do php -l "$f"; done'
```

Al cambiar los estilos, **sube `Version:` en `style.css`**. Es lo que rompe la caché del navegador de quien ya visitó el sitio; sin eso verán los estilos viejos y no sabrán por qué.

## Colaborar

Manda un PR. Dos cosas que pedimos:

- **Tokens, no valores sueltos.** Un `#1e73be` a pelo en un componente rompe el modo oscuro sin que se note hasta que alguien lo mira de noche.
- **Contenido ancho que scrollee solo.** Tablas, bloques de código y diagramas van dentro de su propio `overflow-x: auto`. El `body` no debe scrollear en horizontal nunca.

---

Hecho en Perú 🇵🇪 · Impulsado por [BeyondNetCode](https://beyondnet.info/)
