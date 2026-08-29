#!/usr/bin/env bash
#
# Empaqueta el tema en un zip que WordPress acepta en
# Apariencia → Temas → Añadir nuevo → Subir tema.
#
# El zip debe contener UNA carpeta raíz con el nombre del tema; un zip con los
# ficheros sueltos en la raíz WordPress lo rechaza sin explicar por qué.
set -euo pipefail

SLUG="nestjslatam"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
OUT="$ROOT/dist"
STAGE="$OUT/$SLUG"

VERSION="$(grep -m1 '^Version:' "$ROOT/style.css" | sed 's/Version:[[:space:]]*//' | tr -d '\r')"

# Sólo lo suyo: `rm -rf dist` se llevaba por delante el XML de contenido que
# genera contenido/generar-wxr.py, que también vive aquí.
rm -rf "$STAGE" "$OUT/$SLUG"-*.zip
mkdir -p "$STAGE"

# Sólo lo que el tema necesita en tiempo de ejecución.
cp -R "$ROOT/assets" "$ROOT/inc" "$STAGE/"
cp "$ROOT/style.css" "$ROOT/functions.php" "$STAGE/"
[ -f "$ROOT/screenshot.png" ] && cp "$ROOT/screenshot.png" "$STAGE/"

ZIP="$OUT/$SLUG-$VERSION.zip"
( cd "$OUT" && zip -qr "$(basename "$ZIP")" "$SLUG" )
rm -rf "$STAGE"

printf '\n  %s\n  %s bytes\n\n' "$ZIP" "$(wc -c < "$ZIP" | tr -d ' ')"
