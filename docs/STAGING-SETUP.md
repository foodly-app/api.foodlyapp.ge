# 🚀 FOODLY API - Staging Environment Setup

## 🌐 Domain Configuration

**Staging URL:** `https://staging.foodlyapp.ge`

### DNS Settings
```
staging.foodlyapp.ge    A    YOUR_STAGING_SERVER_IP
```

## ⚙️ Environment Configuration

### Laravel Configuration (.env.staging)
```bash
APP_NAME="FOODLY API"
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.foodlyapp.ge

APP_LOCALE=ka
APP_FALLBACK_LOCALE=en

SESSION_DOMAIN=.foodlyapp.ge
SANCTUM_STATEFUL_DOMAINS=staging.foodlyapp.ge
CORS_ALLOWED_ORIGINS=https://staging.foodlyapp.ge

MAIL_FROM_ADDRESS="staging@foodlyapp.ge"
```

## 🗄️ Database Setup

### Recommended Staging Database
```bash
# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=staging_db
DB_USERNAME=root
DB_PASSWORD=
```

### Database Management
```bash
# Create staging database using provided script
mysql -u root -p < database/staging-setup.sql

# Or manually:
mysql -u root -p
CREATE DATABASE staging_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON staging_db.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
```

## 🚀 Deployment Process

### Option 0: Test Database First (Recommended)
```powershell
# Test database configuration before deployment
.\test-staging-db.ps1
```

### Option 1: Using PowerShell Script (Windows)
```powershell
.\deploy-staging.ps1
```

### Option 2: Using Bash Script (Linux/Mac)
```bash
chmod +x deploy-staging.sh
./deploy-staging.sh
```

### Option 4: Manual Database Setup
### Option 5: Manual Deployment
```bash
# 1. Switch to staging branch
git checkout staging
git merge main

# 2. Set up environment
cp .env.staging .env

# 3. Install dependencies
composer install --optimize-autoloader --no-dev

# 4. Generate key and run migrations
php artisan key:generate
php artisan migrate --force

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🔒 SSL Certificate Setup

### Using Let's Encrypt (Recommended)
```bash
# Install certbot
sudo apt install certbot python3-certbot-apache

# Generate certificate
sudo certbot --apache -d staging.foodlyapp.ge

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 🌐 Web Server Configuration

### Apache Virtual Host
```apache
<VirtualHost *:443>
    ServerName staging.foodlyapp.ge
    DocumentRoot /var/www/staging.foodlyapp.ge/public
    
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/staging.foodlyapp.ge/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/staging.foodlyapp.ge/privkey.pem
    
    <Directory /var/www/staging.foodlyapp.ge/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/staging.foodlyapp.ge_error.log
    CustomLog ${APACHE_LOG_DIR}/staging.foodlyapp.ge_access.log combined
</VirtualHost>
```

### Nginx Configuration
```nginx
server {
    listen 443 ssl;
    server_name staging.foodlyapp.ge;
    root /var/www/staging.foodlyapp.ge/public;
    
    ssl_certificate /etc/letsencrypt/live/staging.foodlyapp.ge/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/staging.foodlyapp.ge/privkey.pem;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🧪 Testing Staging Environment

### Health Check Endpoints
```bash
# Test basic connectivity
curl https://staging.foodlyapp.ge/api/kiosk/test

# Test authentication
curl -X POST https://staging.foodlyapp.ge/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"staging@foodlyapp.ge","password":"staging123","client":"kiosk"}'
```

### Postman Testing
1. Import Collection from: `docs/Collection/FOODLY-API-Complete.postman_collection.json`
2. Import Staging Environment: `docs/Collection/FOODLY-API-Staging.postman_environment.json`
3. Set environment to "FOODLY API - Staging"
4. Test login and protected endpoints

## 📝 Monitoring & Logs

### Log Files
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/apache2/staging.foodlyapp.ge_error.log
tail -f /var/log/nginx/error.log
```

### Health Monitoring
Consider setting up monitoring for:
- Response times
- Error rates
- Database connectivity
- SSL certificate expiration

## 🔄 CI/CD Integration

### GitHub Actions Example
```yaml
name: Deploy to Staging
on:
  push:
    branches: [ staging ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    - name: Deploy to staging server
      run: |
        # Add your deployment commands here
        echo "Deploying to staging.foodlyapp.ge"
```

---

**🎉 Staging environment უმზადესია!**

*📅 Last Updated: September 15, 2025*