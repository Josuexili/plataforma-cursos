#!/bin/zsh

set -e

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
PORT="8000"
URL="http://127.0.0.1:${PORT}"
PID_FILE="${PROJECT_DIR}/storage/app/plataforma-cursos-server.pid"
LOG_FILE="${PROJECT_DIR}/storage/logs/plataforma-cursos-serve.log"

cd "$PROJECT_DIR"

echo "============================================"
echo "  Plataforma de cursos - inici de serveis"
echo "============================================"
echo

if [ -f "public/hot" ]; then
  rm -f "public/hot"
fi

if [ ! -f ".env" ] && [ -f ".env.example" ]; then
  cp ".env.example" ".env"
fi

if [ ! -d "vendor" ]; then
  echo "Instal·lant dependències PHP..."
  composer install || exit 1
fi

if [ ! -d "node_modules" ]; then
  echo "Instal·lant dependències frontend..."
  npm install || exit 1
fi

if [ ! -f "public/build/manifest.json" ]; then
  echo "Generant assets..."
  npm run build || exit 1
fi

mkdir -p "storage/app" "storage/logs"

echo "Preparant base de dades..."
php artisan key:generate --force >/dev/null 2>&1 || true
php artisan migrate:fresh --seed --force || exit 1

EXISTING_PID="$(lsof -tiTCP:${PORT} -sTCP:LISTEN 2>/dev/null || true)"

if [ -n "$EXISTING_PID" ]; then
  EXISTING_COMMAND="$(ps -p "$EXISTING_PID" -o command= 2>/dev/null || true)"

  if echo "$EXISTING_COMMAND" | grep -q "$PROJECT_DIR"; then
    echo "Aturant una instància anterior de la plataforma..."
    kill "$EXISTING_PID" >/dev/null 2>&1 || true
    sleep 1
  elif echo "$EXISTING_COMMAND" | grep -q "php -S 127.0.0.1:${PORT}"; then
    echo "El port ${PORT} l'estava ocupant un altre servidor PHP. Es tancarà per iniciar aquesta plataforma..."
    kill "$EXISTING_PID" >/dev/null 2>&1 || true
    sleep 1
  else
    echo "No s'ha pogut iniciar la plataforma perquè el port ${PORT} està ocupat per un altre procés:"
    echo "$EXISTING_COMMAND"
    echo
    echo "Tanca aquest procés i torna a fer doble clic sobre aquest fitxer."
    read "?Prem Intro per tancar..."
    exit 1
  fi
fi

echo "Iniciant Laravel a ${URL}..."
nohup php artisan serve --host=127.0.0.1 --port="${PORT}" > "$LOG_FILE" 2>&1 &
SERVER_PID=$!
echo "$SERVER_PID" > "$PID_FILE"
sleep 2

if ! lsof -tiTCP:${PORT} -sTCP:LISTEN >/dev/null 2>&1; then
  echo "El servidor no ha arrencat correctament."
  echo "Revisa el log a:"
  echo "$LOG_FILE"
  read "?Prem Intro per tancar..."
  exit 1
fi

open "$URL"

echo
echo "La plataforma està disponible a ${URL}"
echo "S'ha obert automàticament al navegador."
echo
echo "Credencials d'admin:"
echo "  Correu: admin@plataforma-cursos.test"
echo "  Contrasenya: password"
echo
echo "Log del servidor:"
echo "  $LOG_FILE"
echo
read "?Prem Intro per tancar aquesta finestra..."
