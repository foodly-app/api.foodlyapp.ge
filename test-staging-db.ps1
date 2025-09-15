# FOODLY API - Database Setup Test Script (PowerShell)
# Usage: .\test-staging-db.ps1

Write-Host "🗄️ Testing Staging Database Configuration..." -ForegroundColor Green

# Set environment to staging
Copy-Item ".env.staging" ".env" -Force
Write-Host "⚙️ Using staging environment configuration" -ForegroundColor Cyan

# Test database connection
Write-Host "🔌 Testing database connection..." -ForegroundColor Yellow
try {
    $dbTest = php artisan db:show 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Database connection successful!" -ForegroundColor Green
        Write-Host $dbTest -ForegroundColor White
    } else {
        Write-Host "❌ Database connection failed!" -ForegroundColor Red
        Write-Host $dbTest -ForegroundColor Red
        Write-Host ""
        Write-Host "💡 To fix this issue:" -ForegroundColor Yellow
        Write-Host "1. Make sure MySQL is running" -ForegroundColor White
        Write-Host "2. Create staging_db database:" -ForegroundColor White
        Write-Host "   mysql -u root -p < database/staging-setup.sql" -ForegroundColor Cyan
        exit 1
    }
} catch {
    Write-Host "❌ Could not test database connection" -ForegroundColor Red
    exit 1
}

# Test migrations
Write-Host "📋 Testing migrations..." -ForegroundColor Blue
try {
    $migrateTest = php artisan migrate:status 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Migration status retrieved successfully!" -ForegroundColor Green
        Write-Host $migrateTest -ForegroundColor White
    } else {
        Write-Host "⚠️ Migration status check failed (this is normal if migrations haven't run yet)" -ForegroundColor Yellow
        Write-Host $migrateTest -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️ Could not check migration status" -ForegroundColor Yellow
}

# Show current database configuration
Write-Host "📊 Current Database Configuration:" -ForegroundColor Magenta
$envContent = Get-Content ".env" | Where-Object { $_ -match "^DB_" }
foreach ($line in $envContent) {
    Write-Host "  $line" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "🎉 Database test completed!" -ForegroundColor Green
Write-Host "Next step: Run .\deploy-staging.ps1 for full deployment" -ForegroundColor White