#!/bin/bash
set -e

# Fix permissions for storage and cache directories
chown -R appuser:appgroup /var/www/storage /var/www/bootstrap/cache

# Execute the main container command
exec "$@"
