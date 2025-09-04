# 📊 User Activity Logging System

A comprehensive, multi-method user activity logging system for Laravel applications that automatically tracks user actions and provides flexible logging options.

## 🌟 Features

- **🤖 Automatic Logging**: Middleware logs all user activities without code changes
- **🎯 Trait-Based Logging**: Clean, easy-to-use methods for common scenarios
- **🔧 Manual Events**: Full control for complex logging requirements
- **⚡ High Performance**: Queue support and optimized database operations
- **🔍 Rich Data**: Captures IP, user agent, timestamps, and custom context
- **🛡️ Security Aware**: Built-in filtering for sensitive data
- **📈 Scalable**: Designed for high-traffic applications

## 🚀 Quick Start

### 1. Already Installed & Configured!
The system is already set up in your application:
- ✅ Events and listeners registered
- ✅ Middleware configured
- ✅ Database ready
- ✅ Example routes available

### 2. Test It Now
```bash
# All authenticated requests are automatically logged!
# Visit any page while logged in

# Or test with example routes:
curl -X GET http://your-app.com/logging-examples/automatic
```

### 3. Add to Your Controllers
```php
use App\Traits\LogsUserActivity;

class YourController extends Controller
{
    use LogsUserActivity;
    
    public function store(Request $request)
    {
        // Your code...
        $user = User::create($request->validated());
        
        // Add this line
        $this->logCrud('create', 'User', $user->id);
        
        return response()->json($user);
    }
}
```

## 🎯 Three Ways to Log

### Method 1: Automatic (Middleware)
```php
// Zero configuration needed - logs automatically!
Route::middleware(['auth', 'log.activity'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // All routes here are automatically logged
});
```

### Method 2: Trait Methods (Recommended)
```php
// Simple success logging
$this->logSuccess('User profile updated successfully');

// CRUD operations
$this->logCrud('create', 'Order', $order->id);

// Error handling
$this->logError('Payment failed', 'payment_processing', 422);

// Authentication events
$this->logAuth('User logged in', 'login');
```

### Method 3: Manual Events (Full Control)
```php
use App\Events\UserActivityEvent;

event(new UserActivityEvent(auth()->id(), [
    'title' => 'Complex Business Action',
    'status' => 'success',
    'status_code' => 200,
    'message' => 'Custom operation completed',
    'action_type' => 'complex_operation',
], request(), [
    'custom_data' => 'additional context',
    'metrics' => ['duration' => '2.3s']
]));
```

## 📋 What Gets Logged

### Automatic Logging Captures:
- 🌐 All HTTP requests from authenticated users
- 📍 URL paths and route names
- 🔄 Request methods (GET, POST, PUT, DELETE)
- 📊 Response status codes
- 🌍 IP addresses and user agents
- ⏰ Precise timestamps
- 👤 User identification

### Custom Logging Includes:
- 🎯 Business logic events
- 🔐 Authentication activities
- 📝 CRUD operations
- ❌ Error conditions
- 💳 Payment processing
- 📤 File uploads
- 📧 Email sends
- 🔄 Data imports/exports

## 📁 Documentation

| Document | Description | Use When |
|----------|-------------|----------|
| [Quick Start Guide](documentation/QUICK_START_LOGGING.md) | Get up and running in 5 minutes | You want to start logging immediately |
| [Complete Documentation](documentation/USER_ACTIVITY_LOGGING.md) | Comprehensive guide with examples | You need detailed implementation info |
| [API Reference](documentation/LOGGING_API_REFERENCE.md) | Technical API documentation | You're developing advanced features |

## 🎮 Example Routes

Test the system with these pre-built routes:

```
GET  /logging-examples/automatic    # Middleware logging demo
POST /logging-examples/trait        # Trait methods demo  
PUT  /logging-examples/manual       # Manual events demo
PATCH /logging-examples/combined    # All methods demo
```

## 💾 Database Structure

Logs are stored in the `user_logs` table:

```sql
user_logs
├── id (Primary Key)
├── user_id (Foreign Key to users)
├── last_seen (Timestamp)
├── log_details (JSON with activity data)
├── created_at
└── updated_at
```

**Log Details JSON Structure:**
```json
{
    "title": "User Creation Successful",
    "status": "success", 
    "status_code": 201,
    "message": "User 'john_doe' created successfully",
    "action_type": "user_creation",
    "ip": "192.168.1.1",
    "time": "2025-07-08 10:30:45",
    "request_url": "https://example.com/api/users",
    "method": "POST",
    "additional_data": {
        "created_user_id": 123,
        "custom_fields": "..."
    }
}
```

## 🔍 Viewing Logs

### Laravel Tinker
```php
php artisan tinker

// Recent activities
UserLogModel::latest()->take(10)->get();

// Specific user activities  
UserLogModel::where('user_id', 123)->latest()->get();

// Error logs only
UserLogModel::whereJsonContains('log_details->status', 'error')->get();
```

### Database Query
```sql
-- Recent successful activities
SELECT user_id, JSON_EXTRACT(log_details, '$.message') as message, created_at 
FROM user_logs 
WHERE JSON_EXTRACT(log_details, '$.status') = 'success'
ORDER BY created_at DESC 
LIMIT 50;
```

## ⚡ Performance Features

### Queue Support
```php
// Events can be processed in background
php artisan queue:work
```

### Automatic Cleanup
```php
// Keeps only 30 days of logs (configurable)
$schedule->call(function () {
    UserLogModel::where('created_at', '<', now()->subDays(30))->delete();
})->daily();
```

### Smart Filtering
- 🚫 Skips asset files (.css, .js, images)
- 🔄 Avoids logging health checks
- 📁 Filters storage requests
- ⚙️ Configurable skip patterns

## 🛠️ Customization

### Skip Routes
```php
// In LogUserActivityMiddleware.php
$skipRoutes = [
    'admin/logs',      // Don't log admin log viewing
    'api/health',      // Skip health checks
    'debug',           // Skip debug routes
];
```

### Custom Action Types
```php
// Add your own activity categories
'payment_processing',
'user_management', 
'data_export',
'security_event',
'api_access'
```

## 🔒 Security & Privacy

### Sensitive Data Protection
- ❌ Never logs passwords
- 🔐 Masks sensitive form fields
- 🎭 IP anonymization options
- ⏰ Automatic data retention
- 🗑️ GDPR-compliant cleanup

### Access Control
```php
// Only authenticated users are logged
if (auth()->check()) {
    // Log activity
}
```

## 🧪 Testing

The system includes comprehensive test routes and examples:

```php
// Test automatic logging
GET /logging-examples/automatic

// Test trait methods
POST /logging-examples/trait

// Test manual events  
PUT /logging-examples/manual

// Test combined approach
PATCH /logging-examples/combined
```

## 🤝 Integration Examples

### E-commerce
```php
// Order processing
$this->logCrud('create', 'Order', $order->id, [
    'total' => $order->total,
    'items_count' => $order->items->count()
]);

// Payment processing
event(new UserActivityEvent(auth()->id(), [
    'title' => 'Payment Processed',
    'message' => "Payment of $99.99 completed",
    'action_type' => 'payment_success'
]));
```

### User Management
```php
// Registration
$this->logAuth('New user registered', 'registration');

// Profile updates
$this->logCrud('update', 'UserProfile', $user->id);

// Password changes
$this->logAuth('Password changed successfully', 'password_change');
```

### Admin Actions
```php
// User role changes
$this->logSuccess("User role changed to {$newRole}", 'role_change', [
    'target_user_id' => $targetUser->id,
    'old_role' => $oldRole,
    'new_role' => $newRole
]);
```

## 📞 Support

- 📖 [Read the full documentation](documentation/USER_ACTIVITY_LOGGING.md)
- 🚀 [Follow the quick start guide](documentation/QUICK_START_LOGGING.md)  
- 🔧 [Check the API reference](documentation/LOGGING_API_REFERENCE.md)
- 🧪 Test with the example routes: `/logging-examples/*`

## 🎉 Ready to Use!

The system is already configured and logging your user activities. Add custom logging to your controllers with the trait methods, and you'll have comprehensive activity tracking across your entire application!

---

*Built for Laravel applications requiring comprehensive user activity tracking with flexibility, performance, and ease of use.*
