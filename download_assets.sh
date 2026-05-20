#!/bin/bash
# ============================================================
# DMCE — Téléchargement des assets en local
# Exécuter UNE FOIS quand internet est disponible
# Usage : cd /var/www/html/.apps/dmce && bash download_assets.sh
# ============================================================

BASE="/var/www/html/.apps/dmce/public"

echo ">>> Création des répertoires..."
mkdir -p $BASE/vendor/bootstrap/css
mkdir -p $BASE/vendor/bootstrap/js
mkdir -p $BASE/vendor/jquery
mkdir -p $BASE/vendor/line-awesome/css
mkdir -p $BASE/vendor/line-awesome/fonts
mkdir -p $BASE/vendor/toastr
mkdir -p $BASE/vendor/chartjs
mkdir -p $BASE/vendor/select2/css
mkdir -p $BASE/vendor/select2/js
mkdir -p $BASE/vendor/datatables/css
mkdir -p $BASE/vendor/datatables/js

echo ">>> Bootstrap 4.6.2..."
wget -q --show-progress -O $BASE/vendor/bootstrap/css/bootstrap.min.css \
  "https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
wget -q --show-progress -O $BASE/vendor/bootstrap/js/bootstrap.bundle.min.js \
  "https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"

echo ">>> jQuery 3.7.1..."
wget -q --show-progress -O $BASE/vendor/jquery/jquery.min.js \
  "https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"

echo ">>> Line Awesome 1.3.0 (CSS)..."
wget -q --show-progress -O $BASE/vendor/line-awesome/css/line-awesome.min.css \
  "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"

echo ">>> Line Awesome (fonts)..."
LA_BASE="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/fonts"
for FACE in "la-solid-900" "la-regular-400" "la-brands-400"; do
  for EXT in eot ttf woff woff2; do
    wget -q -O "$BASE/vendor/line-awesome/fonts/${FACE}.${EXT}" \
      "${LA_BASE}/${FACE}.${EXT}" 2>/dev/null || true
  done
  # SVG séparé (peut être absent, pas critique)
  wget -q -O "$BASE/vendor/line-awesome/fonts/${FACE}.svg" \
    "${LA_BASE}/${FACE}.svg" 2>/dev/null || true
done

echo ">>> Correction chemin fonts dans le CSS Line Awesome..."
# Le CSS pointe vers ../fonts/ — notre structure est déjà correcte
# Vérifier que le CSS utilise bien ../fonts/
grep -c "fonts/" $BASE/vendor/line-awesome/css/line-awesome.min.css \
  && echo "   Chemins fonts OK" || echo "   ATTENTION : vérifier les chemins fonts"

echo ">>> Toastr 2.1.4..."
wget -q --show-progress -O $BASE/vendor/toastr/toastr.min.css \
  "https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css"
wget -q --show-progress -O $BASE/vendor/toastr/toastr.min.js \
  "https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"

echo ">>> Chart.js 3.9.1..."
wget -q --show-progress -O $BASE/vendor/chartjs/chart.min.js \
  "https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"

echo ">>> Select2 4.1.0 (si utilisé)..."
wget -q -O $BASE/vendor/select2/css/select2.min.css \
  "https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" 2>/dev/null || true
wget -q -O $BASE/vendor/select2/js/select2.min.js \
  "https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js" 2>/dev/null || true

echo ""
echo ">>> Permissions Apache..."
chown -R www-data:www-data $BASE/vendor
chmod -R 755 $BASE/vendor

echo ""
echo "=========================================="
echo "Vérification des fichiers téléchargés :"
echo "=========================================="
for F in \
  "$BASE/vendor/bootstrap/css/bootstrap.min.css" \
  "$BASE/vendor/bootstrap/js/bootstrap.bundle.min.js" \
  "$BASE/vendor/jquery/jquery.min.js" \
  "$BASE/vendor/line-awesome/css/line-awesome.min.css" \
  "$BASE/vendor/toastr/toastr.min.css" \
  "$BASE/vendor/toastr/toastr.min.js" \
  "$BASE/vendor/chartjs/chart.min.js"
do
  if [ -f "$F" ] && [ -s "$F" ]; then
    SIZE=$(du -h "$F" | cut -f1)
    echo "  ✓ $F ($SIZE)"
  else
    echo "  ✗ MANQUANT : $F"
  fi
done

echo ""
echo ">>> DONE. Maintenant mettre à jour admin/layouts/app.blade.php"
echo "    (remplacer les URLs CDN par asset('vendor/...'))"
