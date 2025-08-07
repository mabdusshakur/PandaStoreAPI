# PandaStore API

A beginner-friendly e-commerce RESTful API built with Laravel, featuring proper resource and request classes. This project includes user authentication, product management, order processing, and SSLCommerz payment gateway integration.

## Features

- **Authentication System**: Register, login, and logout functionality with Laravel Sanctum
- **Role-Based Access**: Admin and regular user roles with appropriate middleware protection
- **Product Management**: CRUD operations for products with image upload capability
- **Order Processing**: Create and manage orders with integrated payment processing
- **SSLCommerz Integration**: Secure payment gateway with success, fail, and cancel callbacks
- **API Resources**: Properly structured JSON responses using Laravel's Resource classes
- **Form Request Validation**: Request validation using dedicated Form Request classes

## API Endpoints

### Authentication

- `POST /api/register` - Register a new user
- `POST /api/login` - Login and get authentication token
- `GET /api/logout` - Logout and invalidate token (requires authentication)
- `GET /api/profile` - Get authenticated user profile information

### User Endpoints (Requires Authentication)

- `GET /api/orders` - Get user's orders
- `POST /api/orders` - Place a new order with payment processing

### Admin Endpoints (Requires Admin Role)

- `GET /api/admin/products` - List all products
- `POST /api/admin/products` - Create a new product
- `GET /api/admin/products/{product}` - Get product details
- `PUT /api/admin/products/{product}` - Update a product
- `DELETE /api/admin/products/{product}` - Delete a product

### Payment Callbacks

- `POST /api/payment/success` - Handle successful payment
- `POST /api/payment/fail` - Handle failed payment
- `POST /api/payment/cancel` - Handle cancelled payment

## Project Structure

The project follows Laravel's standard structure with some key components:

### Models
- `User.php` - User model with authentication capabilities
- `Product.php` - Product model with fillable attributes
- `Order.php` - Order model with relationships to User and Product

### Controllers
- `AuthController.php` - Handles user authentication
- `Admin/ProductController.php` - Admin product management
- `User/ProductController.php` - User product listing
- `User/OrderController.php` - Order creation and payment processing

### Resources
- `ProductResource.php` - Transforms product model for admin API responses
- `UserProductResource.php` - Transforms product model for user API responses
- `OrderResource.php` - Transforms order model with product relationship

### Requests
- `StoreProductRequest.php` - Validates product creation
- `UpdateProductRequest.php` - Validates product updates

### Services
- `SSLCommerz.php` - Service class for payment gateway integration

## Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL or SQLite database

### Installation

1. Clone the repository
```bash
git clone https://github.com/mabdusshakur/PandaStoreAPI.git
cd PandaStoreAPI
```

2. Install dependencies
```bash
composer install
```

3. Create environment file
```bash
cp .env.example .env
```

4. Configure your database and SSLCommerz credentials in the `.env` file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pandastore
DB_USERNAME=root
DB_PASSWORD=

SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password
SSLCOMMERZ_SANDBOX_MODE=true
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run migrations and seeders
```bash
php artisan migrate --seed
```

7. Create storage link for product images
```bash
php artisan storage:link
```

8. Start the development server
```bash
php artisan serve
```

## Testing the API

You can use the included Postman collection `PandaStoreAPI.postman_collection.json` to test all API endpoints.