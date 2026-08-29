#!/usr/bin/env python3
"""
Comprueba que el menú del tema y el del XML llevan los mismos destinos.

Había dos listas escritas a mano y se separaron: la guía extendida del CLI en
GitHub y la sección CLI entera desaparecieron del menú del tema sin que nada
avisara, y sólo se detectó porque alguien lo echó de menos. Esto lo convierte
en un fallo de CI.

Se comparan URLs y no textos: un mismo destino puede llamarse distinto en cada
sitio y seguir estando bien.
"""
import pathlib
import re
import sys

RAIZ = pathlib.Path(__file__).resolve().parent.parent


def urls_del_tema() -> set:
    php = (RAIZ / 'inc' / 'menu.php').read_text(encoding='utf-8')
    bloque = re.search(r'function nestjslatam_destinos\(\).*?\n\}', php, re.S)
    if not bloque:
        sys.exit('no se encontró nestjslatam_destinos() en inc/menu.php')

    cuerpo = bloque.group(0)
    urls = {u for u in re.findall(r"'url'\s*=>\s*'([^']+)'", cuerpo)}
    urls |= set(re.findall(r"home_url\(\s*'([^']+)'\s*\)", cuerpo))
    return {u for u in urls if u.startswith(('http', '/'))}


def urls_del_xml() -> set:
    py = (RAIZ / 'contenido' / 'generar-wxr.py').read_text(encoding='utf-8')
    bloque = re.search(r'MENU = \[.*?\n\]', py, re.S)
    if not bloque:
        sys.exit('no se encontró MENU en contenido/generar-wxr.py')

    cuerpo = bloque.group(0)
    urls = set(re.findall(r"'(https://[^']+)'", cuerpo))
    urls |= set(re.findall(r"f'\{DOMINIO\}(/[^']*)'", cuerpo))
    return urls


tema, xml = urls_del_tema(), urls_del_xml()

# El XML tiene «Inicio», que apunta a la portada; el tema no se añade a sí
# mismo un enlace a la raíz porque el logotipo ya lleva ahí.
solo_xml = xml - tema - {'/inicio/'}
solo_tema = tema - xml

if solo_xml or solo_tema:
    if solo_xml:
        print('  Sólo en el XML (faltan en inc/menu.php):')
        for u in sorted(solo_xml):
            print(f'    {u}')
    if solo_tema:
        print('  Sólo en el tema (faltan en generar-wxr.py):')
        for u in sorted(solo_tema):
            print(f'    {u}')
    sys.exit(1)

print(f'  ✓ los dos menús coinciden ({len(tema)} destinos)')
