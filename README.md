# php-website
PHP E-COMMERCE SHOP WEBSITE
==========================

A full-featured PHP-based e-commerce web application with user authentication,
product browsing, cart management, orders, admin dashboard, and search
functionality.

--------------------------------------------------
PROJECT OVERVIEW
--------------------------------------------------
This project is a dynamic online shopping platform built using PHP, MySQL,
HTML, CSS, and JavaScript. It allows users to browse products by categories
and subcategories, add items to a cart, place orders, and track their purchases.
Admins can manage products through a secure dashboard.

The project was built to demonstrate backend logic, database relationships,
security practices, and modern UI structuring.

--------------------------------------------------
FEATURES
--------------------------------------------------

USER FEATURES
- User authentication (login & logout)
- Browse products by category and subcategory
- Product detail pages with stock tracking
- Add to cart using AJAX (no page reload)
- Increase/decrease cart quantities
- Remove items from cart
- Checkout system
- View order history
- Live cart count in navigation bar
- Search products by:
  - Product title
  - Subcategory name
  - Category name

ADMIN FEATURES
- Secure admin-only access
- Add new products
- Edit existing products
- Delete products
- View product list with stock and prices

UI & UX
- Responsive navigation bar with hamburger menu
- Image slider (hero section)
- Modern card-based layouts
- AJAX-powered interactions
- Clean and readable interface

--------------------------------------------------
TECHNOLOGIES USED
--------------------------------------------------
- PHP (Procedural & Prepared Statements)
- MySQL
- HTML5
- CSS3 (Modern responsive design)
- JavaScript (Fetch API & DOM manipulation)
- AJAX
- OBS Studio (used to record project demo)

--------------------------------------------------
PROJECT STRUCTURE
--------------------------------------------------

/css
  - style.css
  - navigations.css
  - product.css
  - cart.css
  - admin.css
  - orders.css
  - search.css

/images
  /categories
  /subcategories
  /slider

/php
  - home.php
  - product.php
  - cart.php
  - orders.php
  - search.php
  - subcategory.php
  - admin.php
  - add_to_cart.php
  - update_cart.php
  - checkout.php
  - connections.php
  - navigations.php
  - footer.php

--------------------------------------------------
DATABASE SETUP
--------------------------------------------------
1. Create a MySQL database
2. Import your SQL file (tables include):
   - users
   - products
   - categories
   - subcategories
   - cart
   - orders
   - ordered_items

3. Update database credentials in:
   connections.php

--------------------------------------------------
INSTALLATION & RUNNING LOCALLY
--------------------------------------------------
1. Install XAMPP / WAMP / MAMP
2. Place the project folder inside:
   htdocs (XAMPP) or www (WAMP)
3. Start Apache and MySQL
4. Import the database
5. Open browser and go to:
   http://localhost/your-project-folder/home.php

--------------------------------------------------
SECURITY NOTES
--------------------------------------------------
- Prepared statements are used to prevent SQL injection
- Sessions are used for authentication
- Admin routes are protected
- Input is sanitized before output (XSS prevention)

--------------------------------------------------
FUTURE IMPROVEMENTS
--------------------------------------------------
- User registration page
- Payment gateway integration
- Product reviews & ratings
- Wishlist feature
- Order status tracking
- Email notifications
- Improved recommendation logic for related products

--------------------------------------------------
AUTHOR
--------------------------------------------------
Built by: Emmanuel Kemboi.  
Role: Web Developer  
Focus: PHP Backend, MySQL, Full-stack fundamentals  

--------------------------------------------------
LICENSE
--------------------------------------------------
This project is for educational and portfolio purposes.
You are free to study, modify, and build upon it.

--------------------------------------------------
DEMO
--------------------------------------------------
A recorded demo of the website is available (OBS Studio recording).

