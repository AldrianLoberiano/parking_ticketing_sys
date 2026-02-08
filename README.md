# Parking Ticketing System

A comprehensive internal parking ticketing system for company employees built with PHP, MySQL, and Bootstrap.

## Features

- **Role-based Access Control**: Separate dashboards for Admins, Security Guards, and Employees
- **Employee & Vehicle Registration**: Manage company employees and their vehicles
- **Parking Management**: Create parking areas and slots with real-time availability tracking
- **Digital Ticketing**: Automated check-in/check-out with timestamps and duration tracking
- **Analytics Dashboard**: Comprehensive parking utilization and performance metrics
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5
- **Real-time Updates**: Live parking status and availability information

## Technology Stack

- **Backend**: PHP 7.4+ with PDO for database operations
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5.1.3, Font Awesome 6.0.0, Custom CSS
- **Architecture**: MVC-like structure with separate controllers, models, and views
- **Security**: Password hashing (bcrypt), input sanitization, prepared statements

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx/IIS)
- PDO extension for PHP
- XAMPP/WAMP/MAMP (recommended for local development)

## Installation

### 1. Environment Setup

1. **Install XAMPP** (recommended) or any web server with PHP and MySQL support
2. **Start Apache and MySQL** services in XAMPP control panel
3. **Clone or download** the project into your web server's document root:
   ```
   htdocs/ (for XAMPP)
   └── parking_ticketing_sys/
   ```

### 2. Database Setup

1. **Create Database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `parking_ticketing`

2. **Import Schema**:
   - Select the `parking_ticketing` database
   - Import the SQL file: `db/schema.sql`

3. **Verify Configuration**:
   - Check `config/database.php` for correct database credentials
   - Default configuration uses:
     - Host: localhost
     - Database: parking_ticketing
     - Username: root
     - Password: (empty)

### 3. Access the Application

- Open your browser and navigate to: `http://localhost/parking_ticketing_sys`
- The application will automatically redirect to the login page

## Usage

### Default Login Credentials

| Role         | Email                | Password |
| ------------ | -------------------- | -------- |
| **Admin**    | admin@company.com    | password |
| **Guard**    | guard@company.com    | password |
| **Employee** | employee@company.com | password |

### User Roles & Features

#### Admin Features

- **User Management**: Create, edit, delete, and manage user accounts
- **Parking Area Management**: Add, edit, and configure parking areas
- **Parking Slot Management**: Create and manage individual parking spaces
- **System Analytics**: View comprehensive reports and statistics
- **Dashboard Overview**: Real-time system metrics and quick actions

#### Security Guard Features

- **Vehicle Check-in**: Quick vehicle entry by license plate number
- **Vehicle Check-out**: Process vehicle exit and calculate parking fees
- **Real-time Availability**: View current parking slot status
- **Ticket Management**: Monitor active parking sessions

#### Employee Features

- **Vehicle Registration**: Register personal vehicles for parking
- **Active Tickets**: View current parking sessions
- **Parking History**: Access complete parking activity records
- **Profile Management**: Update personal information

## Project Structure

```
parking_ticketing_sys/
├── config/
│   └── database.php          # Database configuration
├── controllers/
│   ├── AdminController.php   # Admin operations
│   ├── AuthController.php    # Authentication & authorization
│   ├── EmployeeController.php # Employee operations
│   └── GuardController.php   # Security guard operations
├── db/
│   └── schema.sql           # Database schema & sample data
├── includes/
│   └── functions.php        # Helper functions
├── models/
│   ├── User.php            # User model
│   ├── Vehicle.php         # Vehicle model
│   ├── ParkingArea.php     # Parking area model
│   ├── ParkingSlot.php     # Parking slot model
│   └── Ticket.php          # Parking ticket model
├── public/
│   ├── css/
│   │   └── style.css       # Custom styles
│   └── js/
│       └── script.js       # Client-side scripts
├── views/
│   ├── layouts/
│   │   ├── header.php      # Main header template
│   │   └── footer.php      # Main footer template
│   ├── login.php           # Login page
│   └── admin/              # Admin views
│       ├── dashboard.php
│       ├── manage_users.php
│       ├── manage_areas.php
│       ├── manage_slots.php
│       └── analytics.php
│   └── employee/           # Employee views
│   └── guard/              # Guard views
├── index.php               # Application entry point
└── README.md
```

## Security Features

- **Password Security**: Bcrypt hashing for all passwords
- **Input Validation**: Comprehensive sanitization and validation
- **SQL Injection Prevention**: Prepared statements with PDO
- **Session Management**: Secure PHP session handling
- **Role-based Access**: Strict access control based on user roles
- **CSRF Protection**: Token-based form validation

## Database Schema

The system uses the following main tables:

- **`users`**: User accounts with roles (admin, guard, employee)
- **`vehicles`**: Vehicle information linked to employee owners
- **`parking_areas`**: Parking locations and configurations
- **`parking_slots`**: Individual parking spaces with status tracking
- **`tickets`**: Parking session records with timestamps and fees

## API Endpoints

The system includes several internal API endpoints:

- `/controllers/AuthController.php` - Authentication operations
- `/controllers/AdminController.php` - Administrative functions
- `/controllers/GuardController.php` - Parking operations
- `/controllers/EmployeeController.php` - Employee functions

## Troubleshooting

### Common Issues

- **Database Connection Failed**:
  - Verify MySQL service is running
  - Check credentials in `config/database.php`
  - Ensure database `parking_ticketing` exists

- **Permission Errors**:
  - Check file permissions on the project directory
  - Ensure web server can read/write necessary files

- **Session Issues**:
  - Verify PHP sessions are enabled
  - Check session save path permissions

- **Blank Pages**:
  - Enable PHP error reporting in `php.ini`
  - Check Apache/Nginx error logs
  - Verify all required PHP extensions are installed

- **Styling Issues**:
  - Ensure internet connection for CDN resources (Bootstrap, Font Awesome)
  - Check `public/css/style.css` is loading correctly

### Debug Mode

To enable debug mode, you can modify the database configuration to show detailed error messages during development.

## Development

### Code Style

- Follow PSR-4 autoloading standards
- Use meaningful variable and function names
- Include PHPDoc comments for classes and methods
- Maintain consistent indentation (4 spaces)

### Adding New Features

1. Create appropriate controller methods
2. Add corresponding model classes if needed
3. Create/update view templates
4. Update database schema if required
5. Test thoroughly across all user roles

## Future Enhancements

- [ ] QR code generation for digital tickets
- [ ] RFID/NFC card integration
- [ ] License plate recognition (LPR) system
- [ ] Mobile application API
- [ ] Email/SMS notifications for parking alerts
- [ ] Advanced reporting and analytics
- [ ] Payment gateway integration
- [ ] Multi-language support
- [ ] RESTful API for third-party integrations

## Contributing

This is a complete, runnable system. For modifications:

1. Fork the repository
2. Create a feature branch
3. Follow PHP best practices and maintain the MVC structure
4. Test changes across all user roles
5. Submit a pull request with detailed description

## License

This project is open-source and available under the MIT License.

## Support

For issues or questions:

- Check the troubleshooting section above
- Review the database schema for data structure
- Examine controller logic for business rules
- Verify view templates for UI issues

---

**Last Updated**: February 8, 2026
**Version**: 1.0.0

### Guard Features

- Quick vehicle check-in by plate number
- Vehicle check-out
- Real-time parking availability

### Employee Features

- Register personal vehicles
- View active parking tickets
- Access parking history

## Security Features

- Password hashing using bcrypt
- Input sanitization and validation
- Role-based access control
- Prepared statements to prevent SQL injection
- Session management

## Database Schema

The system uses the following main tables:

- `users`: User accounts with roles
- `vehicles`: Vehicle information linked to owners
- `parking_areas`: Parking locations
- `parking_slots`: Individual parking spaces
- `tickets`: Parking session records

## Future Enhancements

- QR code generation for tickets
- RFID integration
- License plate recognition
- Mobile app API
- Email/SMS notifications
- Advanced reporting

## Troubleshooting

- **Database Connection Issues**: Verify credentials in `config/database.php`
- **Permission Errors**: Check file permissions and web server configuration
- **Session Issues**: Ensure PHP sessions are properly configured
- **Blank Pages**: Check PHP error logs for syntax or runtime errors

## Contributing

This is a complete, runnable system. For modifications, ensure to follow PHP best practices and maintain the MVC-like structure.

##PS! 

This is Undone Project!!! Testing Using GROK CODE FAST 1 MODEL
