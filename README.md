# Solunar - Solar Products Web Application

A web application for managing and displaying solar products with an admin panel for product management.

## Features

- Product management (CRUD operations)
- Admin authentication
- Responsive design
- RESTful API endpoints

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/solunar.git
cd solunar
```

2. Create a MySQL database and import the schema:
```bash
mysql -u root -p
CREATE DATABASE solunar_db;
exit;
mysql -u root -p solunar_db < database/schema.sql
```

3. Configure the database connection:
   - Open `config/database.php`
   - Update the database credentials according to your setup

4. Set up your web server:
   - Point your web server's document root to the project directory
   - Ensure the web server has write permissions for the uploads directory

## Deployment on Railway

1. Create a Railway account at https://railway.app/

2. Install the Railway CLI:
```bash
npm i -g @railway/cli
```

3. Login to Railway:
```bash
railway login
```

4. Initialize your project:
```bash
railway init
```

5. Add your database:
```bash
railway add
```
Select MySQL when prompted.

6. Deploy your application:
```bash
railway up
```

## Security Notes

- Change the default admin credentials in the database
- Use environment variables for sensitive information
- Enable HTTPS in production
- Implement proper input validation and sanitization
- Use prepared statements for all database queries

## API Endpoints

### Products

- GET /api/products/read.php - List all products
- POST /api/products/create.php - Create a new product
- PUT /api/products/update.php - Update a product
- DELETE /api/products/delete.php - Delete a product

## Admin Access

Default admin credentials:
- Username: admin
- Password: admin123

**Important**: Change these credentials immediately after first login.

## License

MIT License 