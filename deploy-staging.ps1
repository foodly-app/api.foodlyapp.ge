# FOODLY API - Staging Deployment Script (PowerShell)
# Usage: .\deploy-staging.ps1

Write-Host "🚀 Starting FOODLY API Staging Deployment..." -ForegroundColor Green

# Check if staging branch exists
$stagingExists = git show-ref --verify --quiet refs/heads/staging 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "📝 Creating staging branch..." -ForegroundColor Yellow
    git checkout -b staging
} else {
    Write-Host "📝 Switching to staging branch..." -ForegroundColor Yellow
    git checkout staging
}

# Merge latest changes from main
Write-Host "🔄 Merging latest changes from main..." -ForegroundColor Blue
git merge main

# Copy staging environment
Write-Host "⚙️ Setting up staging environment..." -ForegroundColor Cyan
Copy-Item ".env.staging" ".env" -Force

# Check database connection
Write-Host "🗄️ Checking database connection..." -ForegroundColor Magenta
try {
    php artisan db:show 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Database connection successful!" -ForegroundColor Green
    } else {
        Write-Host "⚠️ Database connection failed. Please check your database setup." -ForegroundColor Red
        Write-Host "💡 Run: mysql -u root -p < database/staging-setup.sql" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️ Could not test database connection." -ForegroundColor Yellow
}

# Generate application key if not exists
$envContent = Get-Content ".env" -Raw
if ($envContent -notmatch "APP_KEY=base64:") {
    Write-Host "🔑 Generating application key..." -ForegroundColor Magenta
    php artisan key:generate
}

# Run migrations
Write-Host "🗄️ Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force

# Clear and cache configurations
Write-Host "🧹 Clearing caches..." -ForegroundColor DarkYellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

Write-Host "📦 Caching configurations..." -ForegroundColor DarkGreen
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize for staging
Write-Host "⚡ Optimizing for staging..." -ForegroundColor DarkBlue
php artisan optimize

Write-Host "✅ Staging deployment completed successfully!" -ForegroundColor Green
Write-Host "🌐 Staging URL: https://staging.foodlyapp.ge" -ForegroundColor White
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "1. Upload files to staging server" -ForegroundColor White
Write-Host "2. Configure web server (Apache/Nginx)" -ForegroundColor White
Write-Host "3. Set up SSL certificate for staging.foodlyapp.ge" -ForegroundColor White
Write-Host "4. Test API endpoints" -ForegroundColor White