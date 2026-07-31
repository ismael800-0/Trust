#!/bin/bash
set -e

# Default to 8080 if Railway hasn't set PORT yet (e.g. local testing)
PORT="${PORT:-8080}"

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache on port ${PORT}"

exec apache2-foreground
