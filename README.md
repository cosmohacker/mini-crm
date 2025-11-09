# Mini-CRM System

A clean, testable, and extendable mini-CRM system for collecting and processing client requests from a website via a universal feedback widget.

## Setup Instructions

### Prerequisites

- Docker and Docker Compose
- WSL (Windows Subsystem for Linux) - for Windows users

### Docker Commands

#### Start and Build Docker

**Note:** This creates containers and sets up the working Laravel app.

```
docker-compose -f .docker/docker-compose.yml up -d --build
```

#### Start Docker

```
docker-compose -f .docker/docker-compose.yml up -d
```

#### Stop Containers

```
docker-compose -f .docker/docker-compose.yml down --remove-orphans
```

### Installation Steps

1. **Clone the repository:**
   ```
   cd /you_personal_path_for_projects
   ```
   
   ```
   git clone https://github.com/cosmohacker/mini-crm Mini-CRM
   ```
   
   ```
   cd Mini-CRM
   ```

2. **Start and build Docker containers:**
   ```
   docker-compose -f .docker/docker-compose.yml up -d --build
   ```
   
   **Note:** This will create a default blank Laravel project if not already present. See: `.docker/docker-entrypoint.sh`

3. **Update `.env` file with Docker database credentials:**
   
   **Important:** After the build is completed, change the database configuration in your `.env` file:
   
   ```
   DB_CONNECTION=mysql
   DB_HOST=db_mini_crm
   DB_PORT=3306
   DB_DATABASE=mini_crm_db
   DB_USERNAME=rootu
   DB_PASSWORD=rootp
   ```

4. **Run Laravel migrations:**
   ```
   docker-compose -f .docker/docker-compose.yml exec php_mini_crm php artisan migrate
   ```

5. **Seed the database with test data:**
   ```
   docker-compose -f .docker/docker-compose.yml exec php_mini_crm php artisan migrate --seed
   ```

   The application will be available at `http://localhost:8003`

## Test Data

After running `docker-compose -f .docker/docker-compose.yml exec php_mini_crm php artisan migrate --seed`, the following test data will be available:

### Users

Two users are created with the following credentials:

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@admin.com` | `Admin` |
| Manager | `manager@manager.com` | `Manager` |

### Customers

- **10 customers** are generated using factories with:
  - Random names
  - Unique email addresses
  - Phone numbers in E.164 format (e.g., `+1234567890`)

### Tickets

- **1 ticket per customer** (10 tickets total) are generated with:
  - Random subjects (1-5 words)
  - Random text content (1-5 paragraphs)
  - Random status: `new`, `in progress`, or `processed`
  - Associated with their respective customers

### Roles and Permissions

The following roles and permissions are created:

**Admin Role:**
- `view tickets`
- `view customers`
- `view users`

**Manager Role:**
- `view tickets`
- `edit tickets`
- `view customers`

## Widget Embedding

The feedback widget can be embedded on any website using an iframe. The widget is accessible at the `/widget` route.

### Basic iframe Embedding

```html
<iframe 
    src="http://localhost:8003/widget" 
    width="100%" 
    height="600px" 
    frameborder="0"
    style="border: none;">
</iframe>
```

### Responsive iframe Embedding

For a responsive design that adapts to different screen sizes:

```html
<div style="position: relative; padding-bottom: 100%; height: 0; overflow: hidden; max-width: 100%;">
    <iframe 
        src="http://localhost:8003/widget" 
        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
        frameborder="0"
        allowtransparency="true">
    </iframe>
</div>
```

### Example: Full HTML Page with Widget

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Feedback Widget</title>
    <style>
        .widget-container {
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        iframe {
            width: 100%;
            min-height: 700px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="widget-container">
        <iframe src="http://localhost:8003/widget"></iframe>
    </div>
</body>
</html>
```

### Widget Features

The widget includes:
- Customer information form (name, email, phone)
- Ticket submission form (subject, message)
- Optional image upload (max 5MB, formats: JPG, PNG, GIF)
- Real-time validation
- Success/error messaging
- Responsive design

**Note:** The widget route removes the `X-Frame-Options` header to allow embedding in iframes.

## API Documentation

### Base URL

All API endpoints are prefixed with `/api`:

```
http://localhost:8003/api
```

**Note:** The cURL examples in this documentation were generated using [Postman](https://www.postman.com/). You can use Postman or any other API testing tool to test these endpoints.

### Authentication

Currently, the API endpoints are public and do not require authentication. CSRF protection is handled via tokens for form submissions.

### Endpoints

#### 1. Create Customer

**POST** `/api/customers`

Creates a new customer or updates an existing one if the email already exists.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "+1234567890"
}
```

**cURL Example:**
```
curl -X POST http://localhost:8003/api/customers \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "+1234567890"
  }'
```

**Response (201 Created - New Customer):**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "+1234567890",
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

**Response (200 OK - Existing Customer Updated):**
```json
{
    "id": 1,
    "name": "John Doe Updated",
    "email": "john.doe@example.com",
    "phone": "+1234567890",
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:35:00.000000Z"
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `email`: required, valid email format, max 255 characters
- `phone`: optional, must match E.164 format (e.g., `+1234567890`)

**Error Response (422 Unprocessable Entity):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "phone": ["The phone must be in E.164 format."]
    }
}
```

---

#### 2. Get Customers

**GET** `/api/customers`

Retrieves a list of customers. Optionally filter by email.

**Query Parameters:**
- `email` (optional): Filter customers by email address

**cURL Example:**
```
# Get all customers
curl -X GET http://localhost:8003/api/customers \
  -H "Accept: application/json"

# Get customers by email
curl -X GET "http://localhost:8003/api/customers?email=john.doe@example.com" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "phone": "+1234567890",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane.smith@example.com",
        "phone": "+1987654321",
        "created_at": "2024-01-15T11:00:00.000000Z",
        "updated_at": "2024-01-15T11:00:00.000000Z"
    }
]
```

---

#### 3. Create Ticket

**POST** `/api/tickets`

Creates a new ticket with optional image attachment.

**Request Body (multipart/form-data):**
```
customer_id: 1
subject: "Product Inquiry"
text: "I would like to know more about your product features."
status: "new" (optional, defaults to "new")
image: [file] (optional, max 5MB, formats: jpeg, png, jpg, gif)
```

**cURL Example:**
```
curl -X POST http://localhost:8003/api/tickets \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -F "customer_id=1" \
  -F "subject=Product Inquiry" \
  -F "text=I would like to know more about your product features." \
  -F "status=new" \
  -F "image=@/path/to/image.jpg"
```

**Response (201 Created):**
```json
{
    "id": 1,
    "customer_id": 1,
    "customer": {
        "id": 1,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "phone": "+1234567890",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    "subject": "Product Inquiry",
    "text": "I would like to know more about your product features.",
    "status": "new",
    "manager_response_date": null,
    "created_at": "2024-01-15T12:00:00.000000Z",
    "updated_at": "2024-01-15T12:00:00.000000Z",
    "media": [
        {
            "id": 1,
            "file_name": "image.jpg",
            "mime_type": "image/jpeg",
            "size": 245678,
            "url": "http://localhost:8003/storage/1/image.jpg"
        }
    ]
}
```

**Validation Rules:**
- `customer_id`: required, must exist in customers table
- `subject`: required, string, max 255 characters
- `text`: required, string
- `status`: optional, must be one of: `new`, `in progress`, `processed`
- `image`: optional, must be an image file (jpeg, png, jpg, gif), max 5MB

**Business Rules:**
- A customer can only create one ticket per day
- If a customer with the same phone number has already created a ticket today, the request will be rejected
- If a customer with the same email address has already created a ticket today, the request will be rejected

**Error Response (422 Unprocessable Entity):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "customer_id": [
            "A ticket has already been submitted today for this customer."
        ]
    }
}
```

---

#### 4. Get Ticket Statistics

**GET** `/api/tickets/statistics`

Retrieves ticket statistics for today, this week, and this month.

**cURL Example:**
```
curl -X GET http://localhost:8003/api/tickets/statistics
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
    "day": {
        "total": 5,
        "new": 3,
        "in_progress": 1,
        "processed": 1
    },
    "week": {
        "total": 25,
        "new": 15,
        "in_progress": 5,
        "processed": 5
    },
    "month": {
        "total": 100,
        "new": 60,
        "in_progress": 20,
        "processed": 20
    }
}
```

**Response Fields:**
- `day`: Statistics for tickets created today
- `week`: Statistics for tickets created this week (Monday to Sunday)
- `month`: Statistics for tickets created this month
- Each period includes:
  - `total`: Total number of tickets
  - `new`: Number of tickets with status "new"
  - `in_progress`: Number of tickets with status "in progress"
  - `processed`: Number of tickets with status "processed"

---

## Testing

Run the test suite:

```
docker-compose -f .docker/docker-compose.yml exec php_mini_crm php artisan test
```

Or using Composer:

```
docker-compose -f .docker/docker-compose.yml exec php_mini_crm composer run test
```

## Additional Documentation

- **API Editor:** This API documentation was created using [Swagger Editor](https://swagger.io/tools/swagger-editor/)
- [API Documentation (Swagger/OpenAPI)](docs/api-docs.yaml)
- [Architecture & Design Decisions](docs/ARCHITECTURE.md)
