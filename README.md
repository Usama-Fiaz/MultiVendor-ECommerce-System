# MultiVendor E-Commerce System

A comprehensive e-commerce platform that supports multiple vendors, built with Laravel. This system provides a complete solution for online marketplace management with separate interfaces for administrators, sellers, and customers.

## Demo Video Link
[Watch the Demo](https://drive.google.com/file/d/1BeOqyb3Z--2y1PNq4tlHKPuwKwfs4QiO/view?usp=drive_link)

## System Architecture
![System Architecture Diagram](./Architecture-Diagram.png)

The architecture diagram above illustrates the complete system structure, including:
- Core components and their interactions
- User interfaces and their relationships
- Data flow patterns
- Security layers
- External service integrations

## Features

### Admin Features
- **Dashboard Management**
  - Business overview and analytics
  - Order statistics and tracking
  - Earning statistics
  - Custom role management
  - Employee management

- **Product Management**
  - Category management (main, sub, and sub-sub categories)
  - Brand management
  - Product attributes and variations
  - Bulk import/export functionality
  - Product reviews and ratings

- **Order Management**
  - Order processing and tracking
  - Refund management
  - Order status updates
  - Invoice generation
  - Delivery management

- **Promotion Management**
  - Flash deals
  - Feature deals
  - Deal of the day
  - Coupon management
  - Banner management

- **System Settings**
  - Currency management
  - Social login integration
  - Social media chat integration
  - Business settings
  - Announcement management

### Seller Features
- **Dashboard**
  - Business overview
  - Order statistics
  - Earning statistics
  - Stock management

- **Product Management**
  - Add/edit products
  - Product variations
  - Stock management
  - Bulk import/export
  - Barcode generation
  - Product reviews

- **Order Management**
  - Order processing
  - Order status updates
  - Invoice generation
  - Delivery management
  - Refund handling

- **POS System**
  - Quick product view
  - Cart management
  - Customer management
  - Order placement
  - Invoice generation
  - Hold orders

- **Shop Management**
  - Shop profile
  - Vacation mode
  - Bank information
  - Transaction reports

### Customer Features
- **Authentication**
  - Registration with OTP verification
  - Social login integration
  - Password recovery
  - Phone number verification

- **Shopping Experience**
  - Product browsing
  - Cart management
  - Wishlist
  - Order tracking
  - Review and rating system

- **Payment & Shipping**
  - Multiple payment methods
  - Shipping address management
  - Billing address management
  - Reward points system

### Additional Features
- **Multi-language Support**
- **Responsive Design**
- **API Integration**
- **Report Generation**
- **Bulk Operations**
- **Search Functionality**
- **Chat System**

## Technical Stack
- **Backend**: Laravel
- **Frontend**: 
  - Blade Templates
  - JavaScript
  - CSS
- **Database**: MySQL
- **Payment Integration**: Multiple payment gateways
- **Shipping Integration**: ShipRocket

## Installation
1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Configure environment variables
4. Run migrations:
   ```bash
   php artisan migrate
   ```
5. Start the development server:
   ```bash
   php artisan serve
   ```

## Requirements
- PHP >= 7.4
- MySQL >= 5.7
- Node.js >= 12
- Composer
- NPM

## Security Features
- CSRF Protection
- XSS Protection
- SQL Injection Prevention
- Secure Authentication
- Role-based Access Control

## Contributing
Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## License
This project is licensed under the MIT License - see the LICENSE.md file for details.
