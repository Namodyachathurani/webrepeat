# T-ShirtPrinting.lk Website Plan

## Project Overview
A website for a t-shirt printing business with both customer-facing pages and an admin interface.

## Technology Stack
- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP
- Database: MariaDB
- Additional: Bootstrap 5 for responsive design

## Database Structure

### Tables

1. `users`
   - id (Primary Key)
   - username
   - password (hashed)
   - email
   - role (admin/user)
   - created_at

2. `products`
   - id (Primary Key)
   - name
   - description
   - price
   - image_url
   - category
   - created_at
   - updated_at

3. `categories`
   - id (Primary Key)
   - name
   - description

4. `contact_messages`
   - id (Primary Key)
   - name
   - email
   - subject
   - message
   - created_at
   - status

## Page Structure

### 1. Home Page (index.php)
- Hero section with main banner
- Featured products section
- Services overview
- Call to action
- Latest works/portfolio
- Testimonials section

### 2. Products Page (products.php)
- Product categories
- Product grid/list view
- Filtering options
- Product details
- Pricing information

### 3. About Page (about.php)
- Company history
- Mission and vision
- Team section
- Equipment/facilities
- Why choose us

### 4. Contact Us Page (contact.php)
- Contact form
- Location map
- Contact information
- Business hours
- Social media links

### 5. Admin Interface (admin/)
- Login page (admin/login.php)
- Dashboard (admin/index.php)
- Product management
  - Add/Edit/Delete products
  - Manage categories
- Message management
  - View/Respond to contact form submissions
- User management
  - Add/Edit/Delete admin users

## File Structure
```
/
├── index.php
├── products.php
├── about.php
├── contact.php
├── admin/
│   ├── index.php
│   ├── login.php
│   ├── products.php
│   ├── messages.php
│   └── users.php
├── includes/
│   ├── config.php
│   ├── header.php
│   ├── footer.php
│   ├── db.php
│   └── functions.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── uploads/
    └── products/
```

## Security Measures
1. Password hashing
2. SQL injection prevention
3. XSS protection
4. CSRF protection
5. Secure session management
6. Input validation
7. File upload validation

## Implementation Phases

### Phase 1: Setup & Structure
- Set up development environment
- Create database and tables
- Implement basic file structure
- Create includes files

### Phase 2: Frontend Development
- Develop responsive templates
- Implement all customer-facing pages
- Add placeholder content
- Style with CSS

### Phase 3: Admin Interface
- Create login system
- Develop dashboard
- Implement CRUD operations
- Add security measures

### Phase 4: Testing & Optimization
- Test all functionalities
- Cross-browser testing
- Mobile responsiveness
- Performance optimization
- Security testing

## Database Connection Details
Host: zerowebhost.online
Database: zeroweb1_namo
Username: zeroweb1_namo
Password: namopass 