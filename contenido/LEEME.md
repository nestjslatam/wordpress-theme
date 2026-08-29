# Contenido del portal

Las páginas y los artículos de nestjslatam.dev, en git, para poder revisarlos
por PR como cualquier otra cosa.

## Generar el fichero de importación

```bash
python3 contenido/generar-wxr.py
```

Produce `dist/nestjslatam-contenido.xml`. En WordPress:
**Herramientas → Importar → WordPress → Subir archivo e importar.**

Marca **«Descargar e importar archivos adjuntos»** sólo si añades imágenes;
sin ellas no hace falta y la importación es más rápida.

## Es reimportable

El importador de WordPress empareja por `wp:post_id` y `link`, así que volver
a importar el mismo fichero **actualiza** en lugar de duplicar. Se puede
editar un artículo aquí, regenerar y reimportar sin limpiar antes.

Los ids empiezan en 9000 para no chocar con lo que ya haya en el sitio.

## Después de importar, en WordPress

1. **Ajustes → Lectura** → «Tu página de inicio muestra: Una página estática»
   → Página de inicio: **Inicio**, Página de entradas: **Blog**
2. **Apariencia → Menús** → el menú **Principal** ya está creado con sus siete
   entradas; sólo hay que asignarlo a la ubicación «Menú principal»
3. **Apariencia → Personalizar → Identidad del sitio** → sube un logo si
   quieres; sin él, el tema pinta el nombre del sitio como respaldo

## Estructura

```
portada.html        la página de inicio: héroe, blog, novedades, participar
documentacion.html  el índice de documentación, enlazando a docs.nestjslatam.dev
comunidad.html      formas de participar y los repositorios
articulos/          un fichero por artículo, con su cabecera de metadatos
generar-wxr.py      construye el XML
```

Cada artículo empieza con una línea de metadatos:

```html
<!-- meta: cat=Guías | tags=NestJS,DDD | resumen=Una frase. -->
```

`cat` crea la categoría si no existe, `tags` las etiquetas. El generador las
declara como términos en el XML, así que se crean solas al importar.

## Al escribir

Los bloques van con los comentarios de Gutenberg (`<!-- wp:html -->`) para que
WordPress los reconozca como bloques y no como HTML clásico. El HTML dentro
usa las clases del tema — `nl-card`, `nl-note`, `nl-btn` — que están definidas
en `assets/css/`.
