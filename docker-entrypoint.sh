#!/bin/bash
set -e

# Default to 8080 if Railway hasn't set PORT yet (e.g. local testing)
PORT="${PORT:-8080}"

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache on port ${PORT}"

# Forcibly remove conflicting MPM modules at runtime, regardless of build state
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf

# Ensure prefork is enabled (mod_php requires it)
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

echo "----- Enabled MPM modules (after cleanup) -----"
ls -la /etc/apache2/mods-enabled/ | grep -i mpm || echo "No mpm files found"
echo "------------------------------------------------"

exec apache2-foreground