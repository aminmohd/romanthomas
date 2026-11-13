#!/usr/bin/env bash
# Run Composer with PHP 8.5.12 for this project only.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")" && pwd)"
export PHPRC="${ROOT}/config/php-8512.ini"
exec /opt/php-8.5.12/bin/php "$(command -v composer)" "$@"
