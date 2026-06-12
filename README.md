# 🚀 Laravel 12 Enterprise Architecture

[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat-square&logo=docker&logoColor=white)](docker-compose.yml)
[![Database](https://img.shields.io/badge/Database-PostgreSQL%2015+-336791?style=flat-square&logo=postgresql&logoColor=white)](config/database.php)
[![Testing](https://img.shields.io/badge/Testing-Pest%2BPHPUnit-black?style=flat-square)](pest.xml)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen?style=flat-square)]()

A production-ready, enterprise-grade Laravel 12 application demonstrating advanced architecture patterns, security best practices, multi-factor authentication, role-based access control (RBAC), and modular design.

## 🎯 Key Features

### Security & Authentication
- **Multi-Factor Authentication (MFA)**: TOTP, SMS, Email, and Backup Codes
- **JWT API Authentication**: Using Laravel Sanctum with token expiration
- **Role-Based Access Control (RBAC)**: Flexible role and permission system
- **Account Security**: Failed login attempts tracking, account locking, password policies
- **Audit Logging**: Comprehensive audit trail for compliance

### Architecture Patterns
- **Service Layer Pattern**: Business logic separation from controllers
- **Repository Pattern**: Abstraction for data access
- **Event-Driven Architecture**: Event emission and listener-based workflows
- **Middleware Pipeline**: Cross-cutting concerns (auth, logging, rate limiting)
- **Traits**: Reusable functionality (`MultiFactorAuthenticatable`, `HasAuditLog`)
- **Enums**: Type-safe permission and role definitions

### API & Endpoints
- **RESTful API**: Complete CRUD operations with Sanctum
- **Resource Classes**: Consistent JSON response formatting
- **Form Requests**: Built-in validation and authorization
- **Rate Limiting**: Configurable request throttling
- **Pagination**: Query builder integration for large datasets

### Admin Interface
- **Dashboard**: User management, role administration
- **Permission Management**: Fine-grained access control
- **Audit Log Viewer**: Track system actions and changes
- **User Management**: Create, edit, delete users with role assignment

## 🚀 Quick Start

### Prerequisites
- PHP 8.3+
- PostgreSQL 15+
- Redis 7+
- Composer
- Node.js 18+

### Setup with Docker

```bash
# Clone repository
git clone <repository> laravel12-enterprise
cd laravel12-enterprise

# Copy environment file
cp .env.example .env

# Start Docker containers
docker-compose up -d

# Install dependencies
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations and seeders
docker-compose exec app php artisan migrate --seed

# Access application
# App: http://localhost:8000
# Adminer: http://localhost:8080 (for database)
```

### Local Setup

```bash
# Install PHP dependencies
composer install

# Create environment file
cp .env.example .env

# Generate key
php artisan key:generate

# Setup database
php artisan migrate --seed

# Setup Redis queue
php artisan queue:work

# Run development server
php artisan serve
```

## 📝 Default Credentials

After seeding, use these credentials:

```
Email: admin@enterprise.local
Password: SecurePassword123!

Email: user@enterprise.local
Password: SecurePassword123!
```

## 📚 Project Structure

### Models & Relationships
```
User (Authenticatable)
├── roles (BelongsToMany)
├── permissions (BelongsToMany)
├── mfaTokens (HasMany)
├── auditLogs (HasMany)

Role
├── users (BelongsToMany)
└── permissions (BelongsToMany)

Permission
├── roles (BelongsToMany)
└── users (BelongsToMany)

AuditLog
└── user (BelongsTo)

MfaToken
└── user (BelongsTo)
```

### Services Layer

**AuthenticationService**: User login, password management, token generation
**MfaService**: TOTP setup, SMS/Email code sending, code verification
**RoleService**: Role CRUD, permission assignment/revocation
**PermissionService**: Permission management by group
**AuditService**: Activity logging and retrieval

### Middleware

- `VerifyMultiFactor`: Enforce MFA verification
- `CheckPermission`: Permission-based access control
- `AuditLog`: Automatic action logging
- `RateLimiter`: Request throttling

### Events & Jobs

**Events**:
- `UserLogin`: Triggered on successful login
- `PermissionGranted`: Triggered when permission assigned
- `SuspiciousActivity`: Triggered on suspicious actions

**Jobs**:
- `SendMfaCode`: Asynchronous MFA code delivery
- `AuditLogJob`: Background audit logging
- `NotifyAdministrators`: Alert system admins

## 🔐 Security Features

### Authentication & Authorization
```php
// Check user permission
if (auth()->user()->hasPermission('users.create')) {
    // Allowed
}

// Check user role
if (auth()->user()->hasRole('admin')) {
    // Allowed
}

// Grant role
$user->grantRole('admin');

// Revoke role
$user->revokeRole('admin');
```

### Multi-Factor Authentication
```php
// Setup TOTP
$secret = $user->generateTotpSecret();

// Verify TOTP
if ($user->verifyTotpToken($token)) {
    // Valid
}

// Generate backup codes
$codes = $user->generateBackupCodes(10);
$user->saveBackupCodes($codes);

// Verify backup code
if ($user->verifyBackupCode($code)) {
    // Valid (consumed)
}
```

### Audit Logging
```php
// Log action
$auditService->log(
    action: 'user_created',
    model: 'User',
    modelId: $user->id,
    changes: $user->getAttributes()
);

// Get user audit logs
$logs = $auditService->getUserLogs($user);

// Get suspicious activities
$suspicious = $auditService->getSuspiciousActivities();
```

## 🔌 API Endpoints

### Authentication
```
POST   /api/v1/auth/register          # Register new user
POST   /api/v1/auth/login             # Login user
GET    /api/v1/auth/me                # Get current user
POST   /api/v1/auth/logout            # Logout (revoke token)
POST   /api/v1/auth/password          # Change password
POST   /api/v1/auth/refresh           # Refresh token
```

### Users
```
GET    /api/v1/users                  # List users (paginated)
POST   /api/v1/users                  # Create user
GET    /api/v1/users/{id}             # Get user details
PUT    /api/v1/users/{id}             # Update user
DELETE /api/v1/users/{id}             # Delete user
```

### Web Routes
```
GET    /                               # Welcome page
GET    /login                          # Login form
POST   /login                          # Submit login
GET    /dashboard                      # User dashboard
GET    /profile                        # User profile
PUT    /profile                        # Update profile
POST   /password                       # Change password
GET    /auth/mfa/setup                # MFA setup page
POST   /auth/mfa/verify               # Verify MFA token
GET    /admin/roles                    # Admin: Manage roles
```

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Run with Coverage
```bash
php artisan test --coverage
```

### Example Tests
- Authentication (login, logout, MFA)
- User management (CRUD operations)
- Role and permission assignment
- MFA token verification
- Audit logging

## 📊 Database Schema

### Tables
- `users`: User accounts
- `roles`: Role definitions
- `permissions`: Permission definitions
- `role_permission`: Role-permission associations
- `role_user`: User-role assignments
- `user_permission`: Direct user-permission assignments
- `mfa_tokens`: MFA configuration and tokens
- `audit_logs`: Activity audit trail
- `personal_access_tokens`: API tokens (Sanctum)

### Migrations
All migrations are versioned and seeders automatically populate initial data.

## ⚙️ Configuration

### Environment Variables
```env
APP_NAME="Laravel 12 Enterprise"
APP_ENV=production
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel_enterprise
DB_USERNAME=postgres
DB_PASSWORD=secret

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MFA_ENABLED=true
MFA_REQUIRED=true
MFA_DEFAULT_METHOD=totp

SECURITY_REQUIRE_MFA=true
SECURITY_SESSION_TIMEOUT=3600
SECURITY_MAX_LOGIN_ATTEMPTS=5
SECURITY_LOCKOUT_DURATION=900

AUDIT_ENABLED=true
AUDIT_LOG_CHANGES=true
```

### Configuration Files
- `config/auth.php`: Authentication settings
- `config/database.php`: Database connections
- `config/cache.php`: Cache configuration
- `config/queue.php`: Queue configuration

## 🎨 Design Patterns Used

### 1. Service Layer Pattern
Separates business logic from controllers, improving testability and reusability.

```php
class UserController {
    public function store(StoreUserRequest $request) {
        $user = $this->userService->create($request->validated());
        return new UserResource($user);
    }
}
```

### 2. Repository Pattern
Abstracts data access layer for flexibility and testing.

```php
$user = $this->userRepository->findById($id);
$users = $this->userRepository->getByRole('admin');
```

### 3. Event-Driven Architecture
Decouples components through events and listeners.

```php
// Event
event(new UserLogin($user));

// Listener
class LogUserLogin {
    public function handle(UserLogin $event) {
        $this->auditService->log('login', 'User', $event->user->id);
    }
}
```

### 4. Middleware Pipeline
Cross-cutting concerns handled transparently.

```php
Route::post('/users', [UserController::class, 'store'])
    ->middleware('auth:sanctum')
    ->middleware('can:users.create')
    ->middleware('audit.log');
```

### 5. Traits for Reusability
```php
class User extends Authenticatable {
    use MultiFactorAuthenticatable;
    use HasAuditLog;
}
```

### 6. Enums for Type Safety
```php
enum RoleEnum: string {
    case ADMIN = 'admin';
    case USER = 'user';
    
    public function label(): string { ... }
    public function description(): string { ... }
}
```

## 🔄 Workflow Examples

### User Registration with MFA

```php
// 1. Register user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
]);

// 2. Setup TOTP
$secret = $mfaService->enableTotp($user);
// Display QR code to user

// 3. Verify and save TOTP
if ($mfaService->verifyAndSaveTotp($user, $totpToken)) {
    // MFA enabled
}

// 4. Generate backup codes
$codes = $mfaService->generateBackupCodes($user);
```

### Permission-Based Access Control

```php
// 1. Create role with permissions
$role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
$role->syncPermissions(['posts.create', 'posts.edit', 'posts.delete']);

// 2. Assign role to user
$user->grantRole('editor');

// 3. Check permission
if ($user->hasPermission('posts.create')) {
    // Allow user to create posts
}

// 4. Audit the action
AuditLog::create([
    'user_id' => auth()->id(),
    'action' => 'post_created',
    'model' => 'Post',
    'model_id' => $post->id,
]);
```

### API Authentication Flow

```php
// 1. User login
POST /api/v1/auth/login
{
    "email": "user@example.com",
    "password": "password"
}
Response: {
    "success": true,
    "data": {
        "user": { ... },
        "token": "1|xxxxx"
    }
}

// 2. Use token for subsequent requests
GET /api/v1/users
Headers: Authorization: Bearer 1|xxxxx

// 3. Refresh token
POST /api/v1/auth/refresh
Response: {
    "success": true,
    "data": {
        "token": "2|yyyyy"
    }
}

// 4. Logout (revoke token)
POST /api/v1/auth/logout
Headers: Authorization: Bearer 2|yyyyy
```

## 📈 Scalability Considerations

### Database Optimization
- Index on frequently queried columns
- Eager loading with `with()` to prevent N+1 queries
- Database connection pooling for concurrent requests

### Caching Strategy
- User roles and permissions cached in Redis
- Database query results cached with TTL
- Cache invalidation on role/permission changes

### Queue System
- Async MFA code sending
- Background audit logging
- Administrator notifications

### API Rate Limiting
- Per-IP rate limiting (default 60 req/min)
- Per-user rate limiting for authenticated requests
- Configurable thresholds by endpoint

## 🔒 Security Checklist

- [ ] Change default credentials
- [ ] Set strong `APP_KEY` and `JWT_SECRET`
- [ ] Enable HTTPS in production
- [ ] Configure database backups
- [ ] Set up monitoring and alerting
- [ ] Regular security audits
- [ ] Keep dependencies updated
- [ ] Implement CORS properly
- [ ] Use environment variables for secrets
- [ ] Enable audit logging
- [ ] Implement rate limiting
- [ ] Regular penetration testing

## 📖 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Sanctum API Authentication](https://laravel.com/docs/sanctum)
- [RBAC Best Practices](https://cheatsheetseries.owasp.org/cheatsheets/Authorization_Cheat_Sheet.html)
- [Security Considerations](https://laravel.com/docs/security)
- [Testing Guide](https://laravel.com/docs/testing)

## 📄 License

This project is licensed under the Apache License 2.0. See [LICENSE](LICENSE) for details.

## 👥 Contributing

Contributions are welcome! Please follow these guidelines:
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For issues and questions:
- Open a GitHub Issue
- Check existing documentation
- Review test cases for examples

---

**Last Updated**: 2026-01-01
**Version**: 1.0.0
**Status**: Production Ready ✅
