#!/bin/bash
set -e

# Default to 8080 if Railway hasn't set PORT yet (e.g. local testing)
PORT="${PORT:-8080}"

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache on port ${PORT}"

# DIAGNOSTIC: show which MPM modules are actually enabled
echo "----- Enabled MPM modules -----"
ls -la /etc/apache2/mods-enabled/ | grep -i mpm || echo "No mpm files found"
echo "--------------------------------"

exec apache2-foreground