#!/usr/bin/env python3
"""
Genera un fichero WXR importable desde WordPress.

    python3 contenido/generar-wxr.py [dominio]

Produce dist/nestjslatam-contenido.xml, que se sube en
Herramientas -> Importar -> WordPress. Crea las páginas, los artículos, sus
categorías y etiquetas, y el menú de navegación, todo de una vez.

El importador de WordPress es idempotente por `wp:post_id` + `link`: volver a
importar el mismo fichero actualiza en lugar de duplicar, así que se puede
regenerar y reimportar sin limpiar antes.
"""
import html
import pathlib
import re
import sys
from datetime import datetime, timezone

RAIZ = pathlib.Path(__file__).resolve().parent
SALIDA = RAIZ.parent / 'dist' / 'nestjslatam-contenido.xml'
DOMINIO = (sys.argv[1] if len(sys.argv) > 1 else 'https://nestjslatam.dev').rstrip('/')
AUTOR = 'beyondnet.peru'

AHORA = datetime.now(timezone.utc)
FECHA_GMT = AHORA.strftime('%Y-%m-%d %H:%M:%S')
RFC822 = AHORA.strftime('%a, %d %b %Y %H:%M:%S +0000')


def cdata(texto: str) -> str:
    """Envuelve en CDATA, partiendo cualquier ']]>' que apareciera dentro."""
    return '<![CDATA[' + texto.replace(']]>', ']]]]><![CDATA[>') + ']]>'


def leer(nombre: str) -> str:
    return (RAIZ / nombre).read_text(encoding='utf-8').strip()


def meta_de(cuerpo: str) -> dict:
    """Extrae la cabecera '<!-- meta: cat=... | tags=... | resumen=... -->'."""
    m = re.search(r'<!--\s*meta:\s*(.*?)\s*-->', cuerpo)
    if not m:
        return {}
    datos = {}
    for parte in m.group(1).split('|'):
        if '=' in parte:
            clave, valor = parte.split('=', 1)
            datos[clave.strip()] = valor.strip()
    return datos


def sin_meta(cuerpo: str) -> str:
    return re.sub(r'<!--\s*meta:.*?-->\s*', '', cuerpo, count=1)


def slugificar(texto: str) -> str:
    tabla = str.maketrans('áéíóúüñÁÉÍÓÚÜÑ', 'aeiouunAEIOUUN')
    texto = texto.translate(tabla).lower()
    return re.sub(r'-+', '-', re.sub(r'[^a-z0-9]+', '-', texto)).strip('-')


# ── Contenido ──────────────────────────────────────────────────────────────

PAGINAS = [
    ('Inicio',        'inicio',        'portada.html',      0),
    ('Documentación', 'documentacion', 'documentacion.html', 1),
    ('Guías',         'guias',         'guias.html',        2),
    ('Comunidad',     'comunidad',     'comunidad.html',    3),
    # El blog va vacío a propósito: WordPress lo rellena al designarlo como
    # "página de entradas" en Ajustes -> Lectura.
    ('Blog',          'blog',          None,                4),
]

ARTICULOS = [
    ('Estructura contra significado: por qué 400, 422 y 409 no son lo mismo',
     '01-estructura-vs-significado.html'),
    ('El guard que nunca se disparó',
     '02-guard-que-nunca-se-disparo.html'),
    ('Cuando la cobertura miente',
     '03-cuando-la-cobertura-miente.html'),
    ('Un agregado que no podía volver a ser válido',
     '04-agregado-que-no-vuelve-a-ser-valido.html'),
    ('La trampa del orden de construcción',
     '05-orden-de-construccion.html'),
    ('MongoDB para event sourcing: los dos requisitos que nadie te cuenta',
     '06-mongodb-replica-set.html'),
    ('Cómo montar tu primer agregado, paso a paso',
     '07-howto-primer-agregado.html'),
    ('Cómo conectar el CLI a tu agente por MCP',
     '08-howto-cli-mcp.html'),
    ('Cómo modelar dinero sin equivocarte',
     '09-howto-modelar-dinero.html'),
    ('Cómo probar un agregado',
     '10-howto-probar-un-agregado.html'),
    ('Cómo migrar de ddd-lib 3 a 4',
     '11-howto-migrar-ddd-lib-4.html'),
    ('Por qué un borrador puede estar vacío',
     '12-borrador-vacio.html'),
]

# Menú de dos niveles: los hijos llevan `padre` con la etiqueta del suyo.
# Páginas que ya existen en el sitio y este fichero no crea. Se listan para
# que la comprobación de enlaces no las dé por rotas.
YA_EXISTEN = {'about', 'contact'}

MENU = [
    ('Inicio',            f'{DOMINIO}/inicio/',                                        None),
    ('Documentación',     f'{DOMINIO}/documentacion/',                                 None),
    ('Guía de ddd-lib',   'https://docs.nestjslatam.dev/guia/',                        'Documentación'),
    ('Referencia de API', 'https://docs.nestjslatam.dev/guia/api',                     'Documentación'),
    ('Value objects',     'https://docs.nestjslatam.dev/valueobjects/',                'Documentación'),
    ('Event sourcing',    'https://docs.nestjslatam.dev/event-sourcing/',              'Documentación'),
    ('Guías y How-To',    f'{DOMINIO}/guias/',                                         None),
    ('Tu primer agregado', f'{DOMINIO}/como-montar-tu-primer-agregado-paso-a-paso/',   'Guías y How-To'),
    ('Probar un agregado', f'{DOMINIO}/como-probar-un-agregado/',                      'Guías y How-To'),
    ('Modelar dinero',    f'{DOMINIO}/como-modelar-dinero-sin-equivocarte/',           'Guías y How-To'),
    ('Migrar a la 4.0.0', f'{DOMINIO}/como-migrar-de-ddd-lib-3-a-4/',                  'Guías y How-To'),
    ('CLI',               'https://docs.nestjslatam.dev/cli/',                         None),
    ('Guía completa del CLI', 'https://github.com/nestjslatam/ddd-cli/blob/main/docs/GUIDE.md', 'CLI'),
    ('Servidor MCP',      'https://docs.nestjslatam.dev/cli/mcp',                      'CLI'),
    ('Conectarlo a tu agente', f'{DOMINIO}/como-conectar-el-cli-a-tu-agente-por-mcp/', 'CLI'),
    ('Blog',              f'{DOMINIO}/blog/',                                          None),
    ('Comunidad',         f'{DOMINIO}/comunidad/',                                     None),
    ('GitHub',            'https://github.com/nestjslatam',                            None),
]

# ── Construcción ───────────────────────────────────────────────────────────

partes = []
siguiente_id = 9000          # alto, para no chocar con lo que ya existe
categorias, etiquetas = {}, {}


def item(titulo, slug, cuerpo, tipo, orden, cats=(), tags=(), metas=()):
    global siguiente_id
    siguiente_id += 1
    términos = ''.join(
        f'\n\t\t<category domain="category" nicename="{slugificar(c)}">{cdata(c)}</category>'
        for c in cats
    ) + ''.join(
        f'\n\t\t<category domain="post_tag" nicename="{slugificar(t)}">{cdata(t)}</category>'
        for t in tags
    )
    postmeta = ''.join(
        f'\n\t\t<wp:postmeta><wp:meta_key>{cdata(k)}</wp:meta_key>'
        f'<wp:meta_value>{cdata(v)}</wp:meta_value></wp:postmeta>'
        for k, v in metas
    )
    return f"""
\t<item>
\t\t<title>{cdata(titulo)}</title>
\t\t<link>{DOMINIO}/{slug}/</link>
\t\t<pubDate>{RFC822}</pubDate>
\t\t<dc:creator>{cdata(AUTOR)}</dc:creator>
\t\t<guid isPermaLink="false">{DOMINIO}/?p={siguiente_id}</guid>
\t\t<description></description>
\t\t<content:encoded>{cdata(cuerpo)}</content:encoded>
\t\t<excerpt:encoded>{cdata('')}</excerpt:encoded>
\t\t<wp:post_id>{siguiente_id}</wp:post_id>
\t\t<wp:post_date>{cdata(FECHA_GMT)}</wp:post_date>
\t\t<wp:post_date_gmt>{cdata(FECHA_GMT)}</wp:post_date_gmt>
\t\t<wp:comment_status>{cdata('open')}</wp:comment_status>
\t\t<wp:ping_status>{cdata('closed')}</wp:ping_status>
\t\t<wp:post_name>{cdata(slug)}</wp:post_name>
\t\t<wp:status>{cdata('publish')}</wp:status>
\t\t<wp:post_parent>0</wp:post_parent>
\t\t<wp:menu_order>{orden}</wp:menu_order>
\t\t<wp:post_type>{cdata(tipo)}</wp:post_type>
\t\t<wp:post_password>{cdata('')}</wp:post_password>
\t\t<wp:is_sticky>0</wp:is_sticky>{términos}{postmeta}
\t</item>"""


for titulo, slug, fichero, orden in PAGINAS:
    cuerpo = leer(fichero) if fichero else ''
    partes.append(item(titulo, slug, cuerpo, 'page', orden))

for titulo, fichero in ARTICULOS:
    bruto = leer(f'articulos/{fichero}')
    datos = meta_de(bruto)
    cats = [c.strip() for c in datos.get('cat', 'Artículos').split(',')]
    tags = [t.strip() for t in datos.get('tags', '').split(',') if t.strip()]
    for c in cats:
        categorias[slugificar(c)] = c
    for t in tags:
        etiquetas[slugificar(t)] = t
    partes.append(item(titulo, slugificar(titulo), sin_meta(bruto), 'post', 0, cats, tags))

# El menú: cada entrada es un post de tipo nav_menu_item con su metadatos.
# Se declaran como enlaces personalizados para no depender de resolver los ids
# de las páginas, que el importador reasigna.
# Dos pasadas: la primera reserva un id por entrada para que la segunda pueda
# apuntar de hijo a padre. Sin reservarlos antes, un hijo declarado antes que
# su padre quedaría colgando en la raíz.
ids_menu = {etiqueta: siguiente_id + 1 + i for i, (etiqueta, _, _) in enumerate(MENU)}

for posicion, (etiqueta, url, padre) in enumerate(MENU, start=1):
    propio = ids_menu[etiqueta]
    partes.append(
        item(
            etiqueta, f'menu-{slugificar(etiqueta)}', '', 'nav_menu_item', posicion,
            metas=[
                ('_menu_item_type', 'custom'),
                ('_menu_item_menu_item_parent', str(ids_menu[padre]) if padre else '0'),
                ('_menu_item_object_id', str(propio)),
                ('_menu_item_object', 'custom'),
                ('_menu_item_target', ''),
                ('_menu_item_classes', 'a:1:{i:0;s:0:"";}'),
                ('_menu_item_xfn', ''),
                ('_menu_item_url', url),
            ],
        ).replace(
            '</wp:is_sticky>',
            '</wp:is_sticky>\n\t\t<category domain="nav_menu" nicename="principal">'
            + cdata('Principal') + '</category>',
        )
    )

términos = ''.join(
    f'\n\t<wp:category><wp:term_id>{700 + i}</wp:term_id>'
    f'<wp:category_nicename>{slug}</wp:category_nicename>'
    f'<wp:category_parent></wp:category_parent>'
    f'<wp:cat_name>{cdata(nombre)}</wp:cat_name></wp:category>'
    for i, (slug, nombre) in enumerate(sorted(categorias.items()))
) + ''.join(
    f'\n\t<wp:tag><wp:term_id>{800 + i}</wp:term_id>'
    f'<wp:tag_slug>{slug}</wp:tag_slug>'
    f'<wp:tag_name>{cdata(nombre)}</wp:tag_name></wp:tag>'
    for i, (slug, nombre) in enumerate(sorted(etiquetas.items()))
) + (
    '\n\t<wp:term><wp:term_id>900</wp:term_id>'
    '<wp:term_taxonomy>nav_menu</wp:term_taxonomy>'
    '<wp:term_slug>principal</wp:term_slug>'
    f'<wp:term_name>{cdata("Principal")}</wp:term_name></wp:term>'
)

xml = f"""<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
\txmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
\txmlns:content="http://purl.org/rss/1.0/modules/content/"
\txmlns:wfw="http://wellformedweb.org/CommentAPI/"
\txmlns:dc="http://purl.org/dc/elements/1.1/"
\txmlns:wp="http://wordpress.org/export/1.2/">
<channel>
\t<title>NestJS Latam</title>
\t<link>{DOMINIO}</link>
\t<description>Comunidad hispana de NestJS</description>
\t<pubDate>{RFC822}</pubDate>
\t<language>es-ES</language>
\t<wp:wxr_version>1.2</wp:wxr_version>
\t<wp:base_site_url>{DOMINIO}</wp:base_site_url>
\t<wp:base_blog_url>{DOMINIO}</wp:base_blog_url>
\t<wp:author><wp:author_id>1</wp:author_id>
\t\t<wp:author_login>{cdata(AUTOR)}</wp:author_login>
\t\t<wp:author_email>{cdata('beyondnet.peru@gmail.com')}</wp:author_email>
\t\t<wp:author_display_name>{cdata('NestJS Latam')}</wp:author_display_name>
\t\t<wp:author_first_name>{cdata('')}</wp:author_first_name>
\t\t<wp:author_last_name>{cdata('')}</wp:author_last_name></wp:author>{términos}
{''.join(partes)}
</channel>
</rss>
"""

SALIDA.parent.mkdir(exist_ok=True)
SALIDA.write_text(xml, encoding='utf-8')

print(f'  {SALIDA}')
print(f'  {len(PAGINAS)} páginas · {len(ARTICULOS)} artículos · {len(MENU)} entradas de menú')
print(f'  {len(categorias)} categorías · {len(etiquetas)} etiquetas')
print(f'  {SALIDA.stat().st_size} bytes')
