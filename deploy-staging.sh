#!/bin/bash

# FOODLY API - Staging Deployment Script
# Usage: ./deploy-staging.sh

echo "🚀 Starting FOODLY API Staging Deployment..."

# Check if staging branch exists
if ! git show-ref --verify --quiet refs/heads/staging; then
    echo "📝 Creating staging branch..."
    git checkout -b staging
else
    echo "📝 Switching to staging branch..."
    git checkout staging
fi

# Merge latest changes from main
echo "🔄 Merging latest changes from main..."
git merge main

# Copy staging environment
echo "⚙️ Setting up staging environment..."
cp .env.staging .env

# Check database connection
echo "🗄️ Checking database connection..."
if php artisan db:show >/dev/null 2>&1; then
    echo "✅ Database connection successful!"
else
    echo "⚠️ Database connection failed. Please check your database setup."
    echo "💡 Run: mysql -u root -p < database/staging-setup.sql"
fi

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear and cache configurations
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "📦 Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize for staging
echo "⚡ Optimizing for staging..."
php artisan optimize

# Set proper permissions (if on Linux/Unix)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔐 Setting permissions..."
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
fi

echo "✅ Staging deployment completed successfully!"
echo "🌐 Staging URL: https://staging.foodlyapp.ge"
echo ""
echo "📋 Next steps:"
echo "1. Upload files to staging server"
echo "2. Configure web server (Apache/Nginx)"
echo "3. Set up SSL certificate for staging.foodlyapp.ge"
echo "4. Test API endpoints"