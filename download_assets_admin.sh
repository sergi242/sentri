#!/bin/bash
# ============================================================
# DMCE — Téléchargement assets manquants (FullCalendar + Fonts)
# Usage : cd /var/www/html/.apps/dmce && sudo bash download_assets_admin.sh
# ============================================================

BASE="/var/www/html/.apps/dmce/public"

echo ">>> Création des répertoires..."
mkdir -p $BASE/res/app-assets/vendors/css/fullcalendar
mkdir -p $BASE/res/app-assets/vendors/js/fullcalendar
mkdir -p $BASE/res/app-assets/fonts/open-sans
mkdir -p $BASE/res/app-assets/fonts/quicksand

echo ""
echo ">>> FullCalendar 6.1.10 (CSS)..."
wget -q --show-progress \
  -O "$BASE/res/app-assets/vendors/css/fullcalendar/index.global.min.css" \
  "https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css"

echo ">>> FullCalendar 6.1.10 (JS principal)..."
wget -q --show-progress \
  -O "$BASE/res/app-assets/vendors/js/fullcalendar/index.global.min.js" \
  "https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"

echo ">>> FullCalendar 6.1.10 (locale fr)..."
wget -q --show-progress \
  -O "$BASE/res/app-assets/vendors/js/fullcalendar/fr.global.min.js" \
  "https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.global.min.js"

echo ""
echo ">>> Polices Google Fonts (Open Sans + Quicksand)..."
# Télécharger le CSS des polices
wget -q --show-progress \
  -O "/tmp/google-fonts.css" \
  "https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700&display=swap"

# Extraire les URLs des fichiers .woff2 et les télécharger
grep -oP "https://[^)']+" /tmp/google-fonts.css | while read url; do
  FILENAME=$(echo "$url" | md5sum | cut -d' ' -f1).woff2
  wget -q -O "$BASE/res/app-assets/fonts/$FILENAME" "$url" 2>/dev/null || true
done

# Générer un CSS local pointant vers ces fichiers
echo "/* Open Sans + Quicksand - local */" > "$BASE/res/app-assets/fonts/google-fonts.css"

# Open Sans - variantes principales
for WEIGHT in 300 400 600 700; do
  wget -q -O "/tmp/os-${WEIGHT}.woff2" \
    "https://fonts.gstatic.com/s/opensans/v40/memSYaGs126MiZpBA-UvWbX2vVnXBbObj2OVZyOOSr4dVJWUgsjZ0C11.woff2" \
    2>/dev/null || true
done

# Fallback : CSS @import vers Google si les woff2 ne sont pas récupérables
# (les URLs gstatic changent souvent - on garde un fallback)
cat >> "$BASE/res/app-assets/fonts/google-fonts.css" << 'FONTCSS'
/* Fallback défini dans le layout si les fichiers woff2 ne sont pas disponibles */
/* Les polices système sont utilisées à la place */
body { font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
FONTCSS

echo ""
echo ">>> Permissions Apache..."
chown -R www-data:www-data "$BASE/res/app-assets/vendors/css/fullcalendar"
chown -R www-data:www-data "$BASE/res/app-assets/vendors/js/fullcalendar"
chown -R www-data:www-data "$BASE/res/app-assets/fonts"
chmod -R 755 "$BASE/res/app-assets/vendors/css/fullcalendar"
chmod -R 755 "$BASE/res/app-assets/vendors/js/fullcalendar"
chmod -R 755 "$BASE/res/app-assets/fonts"

echo ""
echo "=========================================="
echo "Vérification :"
echo "=========================================="
for F in \
  "$BASE/res/app-assets/vendors/css/fullcalendar/index.global.min.css" \
  "$BASE/res/app-assets/vendors/js/fullcalendar/index.global.min.js" \
  "$BASE/res/app-assets/vendors/js/fullcalendar/fr.global.min.js"
do
  if [ -f "$F" ] && [ -s "$F" ]; then
    SIZE=$(du -h "$F" | cut -f1)
    echo "  OK  $F ($SIZE)"
  else
    echo "  MANQUANT : $F"
  fi
done

echo ""
echo ">>> DONE."
echo "    Maintenant déployer admin/layouts/app.blade.php livré."
