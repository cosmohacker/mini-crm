# Architecture & Design Decisions

This document explains the architectural decisions, library choices, and unique implementation details of the Mini-CRM system.

## Architecture Overview

The Mini-CRM follows a **layered architecture** with clear separation of concerns:

```
┌─────────────────────────────────────┐
│         Presentation Layer          │
│  (Controllers, Resources, Requests)  │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│          Business Logic Layer        │
│            (Services)                │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│          Data Access Layer           │
│         (Repositories)               │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│          Data Layer                  │
│      (Models, Database)              │
└─────────────────────────────────────┘
```

### Layer Responsibilities

1. **Presentation Layer (Controllers)**
   - Handle HTTP requests and responses
   - Validate input using Form Requests
   - Transform data using Resources
   - Return JSON responses for API endpoints

2. **Business Logic Layer (Services)**
   - Contains core business logic
   - Orchestrates operations between repositories
   - Handles complex business rules (e.g., ticket creation limits)

3. **Data Access Layer (Repositories)**
   - Abstracts database operations
   - Provides a clean interface for data retrieval
   - Enables easy testing and swapping of data sources

4. **Data Layer (Models)**
   - Represents database entities
   - Defines relationships
   - Contains model-specific logic (scopes, accessors, mutators)

## Library Choices

### 1. Laravel Framework (^12.0)

**Why Laravel?**
- **Mature and well-documented**: Laravel is one of the most popular PHP frameworks with extensive documentation and community support
- **Rapid development**: Built-in features like authentication, routing, ORM, and migrations speed up development
- **Eloquent ORM**: Provides an intuitive, ActiveRecord-style ORM that makes database operations simple and readable
- **Built-in testing**: PHPUnit integration and testing helpers make it easy to write comprehensive tests
- **Active community**: Large ecosystem of packages and active community support

**Version Choice (^12.0)**
- Latest stable version with modern PHP 8.2+ features
- Improved performance and new features
- Better type safety and static analysis support

### 2. Spatie Laravel Permission (^6.23)

**Why this package?**
- **Role-Based Access Control (RBAC)**: Provides a robust, flexible system for managing user roles and permissions
- **Well-maintained**: Spatie is known for high-quality, well-maintained Laravel packages
- **Database-driven**: Roles and permissions are stored in the database, making them easy to manage and update
- **Middleware integration**: Seamless integration with Laravel's middleware system for route protection
- **Caching**: Built-in caching for better performance

**Use Case in Mini-CRM:**
- Admin and Manager roles with different permission levels
- Easy to extend with additional roles (e.g., Support Agent, Viewer)
- Permissions can be granular (view tickets, edit tickets, view customers, etc.)

**Alternative Considered:**
- Laravel's built-in Gates and Policies: More code required, less flexible for dynamic role management

### 3. Spatie Laravel Media Library (^11.17)

**Why this package?**
- **File management**: Handles file uploads, storage, and retrieval efficiently
- **Multiple collections**: Supports organizing media into different collections (e.g., 'images', 'documents')
- **Image manipulation**: Built-in support for image conversions (thumbnails, resizing, etc.)
- **Storage abstraction**: Works with local storage, S3, and other storage drivers
- **Database tracking**: Maintains metadata about uploaded files in the database
- **Clean API**: Simple, intuitive API for attaching and retrieving media

**Use Case in Mini-CRM:**
- Ticket attachments (images) are stored and associated with tickets
- Easy to retrieve media URLs for display
- Future-proof for adding more media types or collections

**Alternative Considered:**
- Manual file handling: More code, less flexibility, harder to maintain

## Architectural Decisions

### 1. Repository Pattern

**Decision:** Implemented repository pattern for data access.

**Why?**
- **Testability**: Easy to mock repositories in service layer tests
- **Flexibility**: Can swap data sources (e.g., database to API) without changing business logic
- **Single Responsibility**: Repositories handle only data access, services handle business logic
- **Maintainability**: Changes to database structure are isolated to repositories

**Implementation:**
```php
// Repository handles data access
class TicketRepository {
    public function create(array $data): Ticket { ... }
    public function getStatistics(): array { ... }
}

// Service handles business logic
class TicketService {
    public function create(array $data, ?UploadedFile $image) {
        $ticket = $this->ticketRepository->create($data);
        if ($image) {
            $ticket->addMedia($image)->toMediaCollection('images');
        }
        return $ticket;
    }
}
```

### 2. Service Layer Pattern

**Decision:** Introduced a service layer between controllers and repositories.

**Why?**
- **Business Logic Separation**: Keeps controllers thin and focused on HTTP concerns
- **Reusability**: Business logic can be reused across different controllers or contexts
- **Testability**: Business logic can be tested independently of HTTP layer
- **Transaction Management**: Services can handle database transactions for complex operations

### 3. Form Request Validation

**Decision:** Used Laravel's Form Request classes for validation.

**Why?**
- **Separation of Concerns**: Validation logic is separate from controllers
- **Reusability**: Validation rules can be reused
- **Custom Validation**: Easy to add complex validation logic (e.g., ticket creation limits)
- **Authorization**: Can include authorization logic in the same class

**Example:**
```php
// Custom validation in StoreTicketRequest
public function withValidator(Validator $validator): void {
    $validator->after(function ($validator) {
        // Check if customer already created a ticket today
        // Check by customer_id, phone, and email
    });
}
```

### 4. API Resources

**Decision:** Used Laravel API Resources for response transformation.

**Why?**
- **Consistency**: Ensures consistent API response format
- **Flexibility**: Easy to modify response structure without changing models
- **Conditional Data**: Can include/exclude data based on context
- **Relationships**: Easy to include related data when needed

### 4. Widget as Separate Route

**Decision:** Widget is accessible via a dedicated route (`/widget`) that can be embedded in iframes.

**Why?**
- **Universal Embedding**: Can be embedded on any website via iframe
- **Isolation**: Widget has its own view and styling, isolated from the admin panel
- **Security**: Removed `X-Frame-Options` header specifically for the widget route
- **Responsive Design**: Widget is designed to work in various container sizes

**Implementation:**
```php
// WidgetController removes X-Frame-Options header
public function index() {
    $response = response()->view('widgets.index');
    $response->headers->remove('X-Frame-Options');
    return $response;
}
```

### 5. Customer Find-or-Create Pattern

**Decision:** Customer creation uses a "find-or-create" pattern based on email.

**Why?**
- **Data Integrity**: Prevents duplicate customers with the same email
- **User Experience**: Users don't need to remember if they've submitted feedback before
- **Data Updates**: If customer information changes, it's automatically updated
- **Ticket Association**: Existing customers can create multiple tickets over time

**Implementation:**
```php
// CustomerService::findOrCreate
$customer = $this->customerRepository->findByEmail($data['email']);
if ($customer) {
    // Update existing customer
    $this->customerRepository->update($customer, $data);
    return $customer->fresh();
}
// Create new customer
return $this->customerRepository->create($data);
```

### 6. Ticket Creation Limits

**Decision:** Implemented business rules to limit ticket creation (one per customer per day).

**Why?**
- **Prevent Spam**: Reduces abuse and spam submissions
- **Quality Control**: Encourages users to provide complete information in one submission
- **Resource Management**: Prevents overwhelming the system with duplicate requests

**Implementation:**
- Checks by `customer_id` (direct check)
- Checks by `phone` number (if provided)
- Checks by `email` address (if provided)
- All checks are scoped to the current day

### 7. Eloquent Scopes for Statistics

**Decision:** Used Eloquent query scopes for time-based ticket queries.

**Why?**
- **Reusability**: Scopes can be reused across the application
- **Readability**: Makes code more expressive and easier to understand
- **Maintainability**: Time logic is centralized in the model

**Implementation:**
```php
// Ticket Model
public function scopeToday($query) {
    return $query->whereDate('created_at', Carbon::today());
}

public function scopeThisWeek($query) {
    return $query->whereBetween('created_at', [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek(),
    ]);
}
```

### 8. Media Collection Configuration

**Decision:** Configured media library to use a single-file collection for ticket images.

**Why?**
- **Simplicity**: One image per ticket is sufficient for the current use case
- **Performance**: Reduces storage and processing overhead
- **User Experience**: Easier for users to upload a single representative image
- **Future Flexibility**: Can be changed to multi-file if needed

**Implementation:**
```php
// Ticket Model
public function registerMediaCollections(): void {
    $this->addMediaCollection('images')->singleFile();
}
```

### 9. Messages Constants Class

**Decision:** Centralized all user-facing messages in a dedicated `Messages` constants class.

**Why?**
- **Consistency**: Ensures consistent messaging across the entire application
- **Maintainability**: Single source of truth for all messages - easy to update or translate
- **Type Safety**: IDE autocomplete and static analysis catch typos and missing constants
- **Reusability**: Messages can be reused across different parts of the application
- **Internationalization Ready**: Centralized messages make it easier to implement i18n in the future
- **Code Readability**: Using constants like `Messages::TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY` is more readable than hardcoded strings
- **Refactoring Safety**: Changing a message only requires updating one location

**Implementation:**
```php
// App\Constants\Messages
class Messages
{
    // Ticket Validation Messages
    public const TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY = '...';
    public const TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY_BY_PHONE = '...';
    public const TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY_BY_EMAIL = '...';
    
    // Customer Validation Messages
    public const CUSTOMER_VALIDATION_PHONE_E164_FORMAT = '...';
    
    // Ticket Success/Error Messages
    public const TICKET_STATUS_UPDATED_SUCCESSFULLY = '...';
    public const TICKET_STATUS_UPDATE_FORBIDDEN = '...';
    
    // Authentication Messages
    public const AUTH_CREDENTIALS_DO_NOT_MATCH = '...';
}

// Usage in Form Requests
use App\Constants\Messages;

$validator->errors()->add('customer_id', Messages::TICKET_VALIDATION_ALREADY_SUBMITTED_TODAY);
```

**Categories of Messages:**
- **Validation Messages**: Used in Form Request validators for user input validation
- **Success Messages**: Success notifications for user actions
- **Error Messages**: Error notifications for failed operations
- **Authentication Messages**: Login and authentication-related messages

**Alternative Considered:**
- Hardcoded strings: Less maintainable, prone to inconsistencies, harder to translate
- Language files: More complex setup for a small application, but could be migrated to this approach later

## Security Considerations

### 1. CSRF Protection
- All form submissions (including widget) use CSRF tokens
- API endpoints that accept form data require CSRF tokens

### 2. Input Validation
- All inputs are validated using Form Requests
- Phone numbers must match E.164 format
- Email addresses are validated
- File uploads are restricted by type and size

### 3. File Upload Security
- Only image files are accepted (jpeg, png, jpg, gif)
- Maximum file size: 5MB
- Files are stored securely in the storage directory

### 4. Role-Based Access Control
- Admin panel routes are protected by authentication and role middleware
- Only users with 'admin' or 'manager' roles can access the admin panel

## Performance Optimizations

### 1. Eager Loading
- Relationships are eager loaded to prevent N+1 query problems
- Example: `Ticket::with(['customer', 'media'])->find($id)`

### 2. Query Scopes
- Reusable query scopes reduce code duplication
- Improve query readability and maintainability

### 3. Database Indexing
- Primary keys and foreign keys are automatically indexed
- Email addresses should be indexed for customer lookups (recommended)

## Future Extensibility

The architecture is designed to be easily extensible:

1. **Additional Media Types**: Media library can handle documents, videos, etc.
2. **More Roles**: Permission system can easily accommodate new roles
3. **API Authentication**: Can add API tokens or OAuth for authenticated API access
4. **Notifications**: Can integrate email/SMS notifications for ticket updates
5. **Advanced Filtering**: Repository pattern makes it easy to add complex filtering
6. **Multi-tenancy**: Architecture can be extended to support multiple organizations

## Testing Strategy

### Unit Tests
- Test individual components (services, repositories) in isolation
- Mock dependencies for clean unit tests

### Feature Tests
- Test complete workflows (e.g., creating a ticket via API)
- Test business rules (e.g., ticket creation limits)
- Test authentication and authorization

### Test Data
- Factories for generating test data
- Seeders for consistent test database state

## Code Quality

### Standards
- Follows PSR-12 coding standards
- Uses Laravel Pint for code formatting
- Type hints for better IDE support and static analysis

### Documentation
- PHPDoc comments for classes and methods
- Clear method and variable names
- Comprehensive README and API documentation

