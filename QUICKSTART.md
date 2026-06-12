# Laravel 12 Enterprise Architecture - Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Option 1: Using Docker (Recommended)

```bash
# 1. Clone and setup
cd laravel12-enterprise
cp .env.example .env

# 2. Start the project
docker-compose up -d

# 3. Wait for containers (about 30 seconds)
sleep 30

# 4. Run setup
bash bootstrap.sh

# 5. Access the application
# App: http://localhost:8000
# Database: http://localhost:8080
```

**Login with**:
- Email: `admin@enterprise.local`
- Password: `SecurePassword123!`

### Option 2: Local Development

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate --seed

# 4. Start Redis queue (in separate terminal)
php artisan queue:work

# 5. Start server
php artisan serve

# Access at http://localhost:8000
```

## 📚 Key Files to Understand

### Configuration
- `config/auth.php` - Authentication settings
- `config/database.php` - Database connections
- `.env.example` - Environment variables

### Core Application
- `app/Models/User.php` - User model with MFA support
- `app/Services/` - Business logic layer
- `app/Http/Controllers/` - Request handlers
- `routes/web.php` - Web routes
- `routes/api.php` - API routes

### Database
- `database/migrations/` - Schema definitions
- `database/seeders/` - Initial data

### Features
- `app/Traits/MultiFactorAuthenticatable.php` - MFA functionality
- `app/Enums/` - Type-safe permissions and roles
- `app/Events/` - Event-driven architecture
- `app/Jobs/` - Queue jobs

## 🔑 Default Roles & Permissions

### Roles
- **Super Admin**: Full system access
- **Admin**: Administrative access
- **Manager**: Team management
- **User**: Standard access
- **Guest**: Read-only access

### Permissions
- `users.*` - User management (view, create, edit, delete)
- `roles.*` - Role management
- `permissions.*` - Permission management
- `audit.view` - View audit logs
- `settings.manage` - System settings
- `security.manage` - Security settings

## 🔐 Setting Up MFA

### For Authenticated Users

```bash
# Visit the MFA setup page
http://localhost:8000/auth/mfa/setup

# Steps:
# 1. Click "Enable TOTP"
# 2. Scan QR code with Authenticator app
# 3. Enter 6-digit code
# 4. Save backup codes
```

### Backup Codes
- 10 single-use codes
- Store securely for emergency access
- Each code works once only

## 🔌 API Examples

### Register User
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@enterprise.local",
    "password": "SecurePassword123!"
  }'
```

### Get Current User
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### List Users (Admin)
```bash
curl -X GET http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🧪 Running Tests

```bash
# All tests
php artisan test

# Feature tests only
php artisan test --testsuite=Feature

# Unit tests only
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage

# Specific test file
php artisan test tests/Feature/AuthTest.php
```

## 📊 Database Management

### Using Docker (Adminer)
- Visit: http://localhost:8080
- Server: `postgres`
- Username: `postgres`
- Password: `secret`
- Database: `laravel_enterprise`

### Artisan Commands
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Reset database
php artisan migrate:reset
```

## 🎨 Customization Guide

### Adding New Permission

1. **Add to Enum** (`app/Enums/PermissionEnum.php`)
```php
case MY_PERMISSION = 'my.permission';

public function label(): string {
    return match ($this) {
        self::MY_PERMISSION => 'My Permission',
    };
}
```

2. **Run Seeder**
```bash
php artisan db:seed PermissionSeeder
```

### Creating New Role

1. **Create Role**
```php
$role = Role::create([
    'name' => 'Custom Role',
    'slug' => 'custom_role',
    'description' => 'Description here',
]);

// Attach permissions
$role->syncPermissions(['users.view', 'users.edit']);
```

### Adding MFA to User Flow

```php
// In controller
if ($user->hasMfaEnabled()) {
    return redirect('/auth/mfa/verify')
        ->with('user_id', $user->id);
}
```

## 🔧 Common Issues & Solutions

### Issue: Migrations fail on Docker
```bash
# Solution: Check database connection
docker-compose logs postgres

# Or rebuild containers
docker-compose down -v
docker-compose up -d
```

### Issue: Queue jobs not processing
```bash
# Solution: Start queue worker
php artisan queue:work

# Or with supervisor in production
supervisor manage queue:work
```

### Issue: Permission denied on storage
```bash
# Solution: Fix permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

## 📈 Monitoring & Debugging

### View Logs
```bash
# Docker
docker-compose logs -f app

# Local
tail -f storage/logs/laravel.log
```

### Enable Debug Mode
```env
# In .env
APP_DEBUG=true
```

### Database Queries
```php
// In code
\DB::listen(function ($query) {
    \Log::info($query->sql);
});
```

### Audit Logs
```bash
# View recent activities
SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 20;
```

## 🚀 Deployment Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Set strong `APP_KEY`
- [ ] Configure database (PostgreSQL)
- [ ] Configure Redis connection
- [ ] Enable HTTPS
- [ ] Set up queue worker
- [ ] Configure mail service
- [ ] Enable audit logging
- [ ] Set rate limiting
- [ ] Configure backups
- [ ] Setup monitoring/alerting

## 📞 Getting Help

1. **Check README.md** - Comprehensive documentation
2. **Review Test Files** - See usage examples
3. **Check Laravel Docs** - https://laravel.com/docs
4. **Look at Code Comments** - Self-documenting code

## 🎓 Learning Resources

- Service Layer Pattern explanation
- RBAC best practices
- MFA implementation guide
- Event-driven architecture
- Testing patterns

See [README.md](README.md) for detailed documentation.

---

**Ready to start?** Follow Option 1 or 2 above and you'll be up and running in minutes!
