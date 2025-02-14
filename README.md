# T-Shirt Printing Website

A complete e-commerce website for a t-shirt printing business, built with PHP and MariaDB.

## Features

- Responsive design using modern CSS
- Product catalog with categories
- Contact form with admin management
- Admin dashboard with:
  - Product management
  - Category management
  - Message management
  - User management
- Secure authentication system
- Docker-based development environment

## Prerequisites

- Docker
- Docker Compose
- Git

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <repository-name>
   ```

2. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

3. The application will be available at:
   - Website: http://localhost
   - phpMyAdmin: http://localhost:8080

## Default Admin Credentials

- Username: admin
- Password: admin123

**Important**: Change these credentials after first login!

## Directory Structure

```
/
├── admin/              # Admin panel files
├── assets/            # Static assets (CSS, JS, images)
├── database/          # Database initialization files
├── includes/          # PHP includes (config, functions)
├── uploads/           # User uploaded files
├── Dockerfile         # PHP application container
└── docker-compose.yml # Docker services configuration
```

## Development

The Docker setup includes:
- PHP 8.1 with Apache
- MariaDB 10.6
- phpMyAdmin

### Working with Docker

- Start containers: `docker-compose up -d`
- Stop containers: `docker-compose down`
- View logs: `docker-compose logs -f`
- Rebuild containers: `docker-compose up -d --build`

### Database Management

- Access phpMyAdmin at http://localhost:8080
- Database credentials are in docker-compose.yml
- Initial database structure and data are in database/init.sql

## File Upload Directories

The following directories are used for file uploads:
- Product images: `assets/images/products/`
- General uploads: `uploads/`

Make sure these directories have proper write permissions.

## Security Considerations

1. Change the default admin credentials
2. Keep Docker and dependencies updated
3. Regular database backups
4. Monitor error logs
5. Use HTTPS in production

## Production Deployment

For production deployment:

1. Use proper SSL/TLS certificates
2. Configure proper database credentials
3. Set up regular backups
4. Configure proper file permissions
5. Enable PHP OPcache
6. Set up proper email configuration

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 