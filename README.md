# Web Application for Managing Products and Orders

## Overview

This project is a PHP-based web application that allows role-based management of users, products, categories, and orders. It includes features for order tracking, user management, and activity logging, supporting administrators, moderators, and regular users.

## Features

### Users

- Administrators and moderators can:
  - View users
  - Add users
  - Update user information
  - Delete users
  - See user spending information

### Products and Categories

- Administrators and moderators can:
  - Manage products (add, update, delete)
  - Manage product categories
  - View products with total spending
  - View product categories with total spending

### Orders

- Administrators and moderators can:
  - Manage orders
  - View customer, total price, and date for each order

- Regular users can:
  - Create orders
  - View their cart
  - Complete orders

### Logs

- Only administrators can access activity logs via `logs.php`

### Authentication

- Users can:
  - Log in and log out
  - Access features based on their role (admin, moderator, user)

## Login

To log in, open `login.php` and use one of the following accounts:

### Administrator

- **Username:** danilo123  
- **Password:** danilo1

### Moderator

- **Username:** aleksandar123  
- **Password:** aleksandar1

### Regular User

- **Username:** marko123  
- **Password:** marko1

## Managing Products and Orders

- Administrators and moderators can:
  - Manage users, products, categories, and orders through the interface

- Regular users can:
  - Create orders
  - View their carts

## Accessing Logs

- Logs are available only to administrators via the `logs.php` page.

## Technical Details

- **Technologies used:** PHP, MySQL

## Contact

If you have any questions or suggestions, feel free to reach out:

- **Email:** danilodasic8@gmail.com
- **GitHub:** [danilodasic4](https://github.com/danilodasic4)