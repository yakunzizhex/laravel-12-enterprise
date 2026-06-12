#!/bin/bash

echo "🚀 Laravel 12 Enterprise Setup"
echo "================================"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

echo "✅ Docker found"

# Start containers
echo "📦 Starting Docker containers..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 10

# Install dependencies
echo "📚 Installing PHP dependencies..."
docker-compose exec -T app composer install

# Generate key
echo "🔑 Generating application key..."
docker-compose exec -T app php artisan key:generate

# Run migrations
echo "📊 Running migrations..."
docker-compose exec -T app php artisan migrate

# Run seeders
echo "🌱 Running seeders..."
docker-compose exec -T app php artisan db:seed

# Set permissions
echo "🔒 Setting permissions..."
docker-compose exec -T app chmod -R 775 storage bootstrap/cache

echo ""
echo "✨ Setup complete!"
echo ""
echo "📍 Access points:"
echo "   - Application: http://localhost:8000"
echo "   - Database (Adminer): http://localhost:8080"
echo ""
echo "🔐 Default credentials:"
echo "   - Email: admin@enterprise.local"
echo "   - Password: SecurePassword123!"
echo ""
echo "🔧 Useful commands:"
echo "   - View logs: docker-compose logs -f app"
echo "   - Run artisan: docker-compose exec app php artisan <command>"
echo "   - Run tests: docker-compose exec app php artisan test"
echo "   - Stop services: docker-compose down"
echo ""
