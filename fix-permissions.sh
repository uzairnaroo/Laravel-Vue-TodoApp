#!/bin/bash

# Set permissions on host
sudo chown -R $(whoami):$(whoami) backend/storage backend/bootstrap/cache
chmod -R 775 backend/storage backend/bootstrap/cache

# Set permissions inside Docker container
docker-compose exec backend bash -c "chmod -R 775 /var/www/storage /var/www/bootstrap/cache"

echo "Permissions have been fixed."
