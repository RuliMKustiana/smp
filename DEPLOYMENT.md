# Deployment Guide - Sistem Manajemen Proyek

## 🚀 Production Deployment

### 1. Server Requirements

#### Minimum Specifications
- **OS**: Ubuntu 20.04+ / CentOS 8+ / Windows Server 2019+
- **PHP**: 8.2+ dengan extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Web Server**: Nginx 1.18+ atau Apache 2.4+
- **Database**: MySQL 8.0+ / PostgreSQL 13+ / MariaDB 10.3+
- **Memory**: 512MB minimum, 2GB recommended
- **Storage**: 1GB minimum untuk aplikasi + space untuk uploads

#### PHP Extensions Required
```bash
# Ubuntu/Debian
sudo apt-get install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath

# CentOS/RHEL
sudo yum install php82 php82-fpm php82-mysqlnd php82-xml php82-gd php82-curl php82-mbstring php82-zip php82-bcmath
```

### 2. Environment Setup

#### Clone Repository
```bash
git clone https://github.com/your-repo/sistem-manajemen-proyek.git
cd sistem-manajemen-proyek
```

#### Install Dependencies
```bash
# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies dan build assets
npm install
npm run build
```

#### Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### Configure `.env` for Production
```env
APP_NAME="Sistem Manajemen Proyek"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_manajemen_proyek
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Mail Configuration (untuk notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Cache Configuration
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# File Storage
FILESYSTEM_DISK=local
```

### 3. Database Setup

#### Create Database
```sql
-- MySQL
CREATE DATABASE sistem_manajemen_proyek CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sistem_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON sistem_manajemen_proyek.* TO 'sistem_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate --force

# Run seeders (creates default admin user)
php artisan db:seed --force

# Create storage link
php artisan storage:link
```

### 4. Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    root /var/www/sistem-manajemen-proyek/public;

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

    # File upload size
    client_max_body_size 20M;
}
```

#### Apache Configuration (.htaccess)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# File upload size
php_value upload_max_filesize 20M
php_value post_max_size 20M
```

### 5. SSL Certificate (Recommended)

#### Using Let's Encrypt
```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### 6. Security Hardening

#### File Permissions
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/sistem-manajemen-proyek
sudo chmod -R 755 /var/www/sistem-manajemen-proyek
sudo chmod -R 775 /var/www/sistem-manajemen-proyek/storage
sudo chmod -R 775 /var/www/sistem-manajemen-proyek/bootstrap/cache
```

#### Firewall Configuration
```bash
# UFW (Ubuntu)
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

#### Hide Server Information
```nginx
# Add to nginx.conf
server_tokens off;

# Add to php.ini
expose_php = Off
```

### 7. Performance Optimization

#### Caching
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache when needed
php artisan optimize:clear
```

#### OPcache Configuration (php.ini)
```ini
[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### 8. Monitoring & Logging

#### Log Configuration
```bash
# Create log directory
sudo mkdir -p /var/log/sistem-manajemen-proyek
sudo chown www-data:www-data /var/log/sistem-manajemen-proyek

# Logrotate configuration
sudo nano /etc/logrotate.d/sistem-manajemen-proyek
```

#### Logrotate Configuration
```
/var/www/sistem-manajemen-proyek/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
}
```

#### Basic Monitoring Script
```bash
#!/bin/bash
# monitor.sh - Basic health check script

URL="https://your-domain.com"
STATUS=$(curl -o /dev/null -s -w "%{http_code}\n" $URL)

if [ $STATUS -ne 200 ]; then
    echo "Website is down! Status: $STATUS" | mail -s "Website Alert" admin@your-domain.com
fi
```

### 9. Backup Strategy

#### Database Backup Script
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/sistem-manajemen-proyek"
DB_NAME="sistem_manajemen_proyek"
DB_USER="sistem_user"
DB_PASS="secure_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /var/www/sistem-manajemen-proyek/storage/app/public

# Keep only 30 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

#### Crontab for Automated Backups
```bash
# Daily backup at 2 AM
0 2 * * * /path/to/backup.sh
```

### 10. Troubleshooting

#### Common Issues

**Issue**: 500 Internal Server Error
```bash
# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log

# Check Laravel logs
tail -f /var/www/sistem-manajemen-proyek/storage/logs/laravel.log

# Check file permissions
ls -la /var/www/sistem-manajemen-proyek/storage
```

**Issue**: File uploads not working
```bash
# Check upload directory permissions
chmod 775 storage/app/public

# Recreate storage link
php artisan storage:link

# Check PHP upload settings
php -i | grep upload
```

**Issue**: Database connection failed
```bash
# Test database connection
mysql -u sistem_user -p sistem_manajemen_proyek

# Check .env configuration
grep DB_ .env
```

#### Performance Issues
```bash
# Enable query logging
# Add to .env: LOG_QUERY=true

# Monitor slow queries
# Add to my.cnf: slow_query_log = 1

# Check disk space
df -h

# Check memory usage
free -h
```

### 11. Update Deployment

#### Zero-Downtime Deployment Script
```bash
#!/bin/bash
# deploy.sh

APP_DIR="/var/www/sistem-manajemen-proyek"
BACKUP_DIR="/var/backups/app-backup-$(date +%Y%m%d_%H%M%S)"

echo "Starting deployment..."

# Create backup
cp -r $APP_DIR $BACKUP_DIR

# Pull latest code
cd $APP_DIR
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload nginx
sudo systemctl reload php8.2-fpm

echo "Deployment completed successfully!"
```

### 12. Production Checklist

#### Pre-Deployment
- [ ] Environment variables configured
- [ ] Database created and migrated
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] Firewall configured
- [ ] Backup strategy implemented
- [ ] Monitoring setup
- [ ] Mail configuration tested

#### Post-Deployment
- [ ] Application accessible via domain
- [ ] Login functionality working
- [ ] File uploads working
- [ ] Email notifications working
- [ ] All user roles accessible
- [ ] Performance acceptable
- [ ] Security headers present
- [ ] Logs being written
- [ ] Backup running automatically

#### Security Verification
- [ ] Debug mode disabled (APP_DEBUG=false)
- [ ] Strong database passwords
- [ ] File upload restrictions in place
- [ ] CSRF protection enabled
- [ ] Server information hidden
- [ ] Regular security updates planned

## 📞 Support

Untuk support deployment atau issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server logs: `/var/log/nginx/error.log`
3. Enable debug mode temporarily untuk detailed errors
4. Review this deployment guide
5. Contact system administrator
