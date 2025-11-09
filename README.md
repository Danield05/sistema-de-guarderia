# PEQUE CONTROL - Nursery Management System

A comprehensive management system for childcare centers that facilitates educational and operational administration. Developed with modern technologies to provide an intuitive and efficient user experience.

## ✨ Key Features

### 👥 User Management
- Registration and administration of teachers/administrators
- Role-based permission system
- Profile management with images
- Secure access control

### 👨‍👩‍👧‍👦 Academic Management
- Classroom and section management
- Student registration and tracking
- Attendance control system
- Assignment of responsible adults

### 🏥 Medical Information
- Disease tracking and management
- Medication administration records
- Allergy management system
- Medical consultation logging

### 🔐 Permission System
- Complete system permission management
- Permission activation/deactivation
- Granular access control

### 📊 Dashboard
- Modern dashboard with statistics
- Responsive and modern interface
- Intuitive navigation

### 👤 User Profile
- Personal profile editing
- Password change functionality
- Profile picture upload
- Account information display

### 📱 Modern Interface
- Modern frontend design with custom CSS
- Expressive icons and attractive colors
- Responsive layout for all devices
- Glassmorphism effects and animations

## 🛠️ Technologies Used

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **CSS Framework**: Bootstrap 4.6.2
- **Icons**: Font Awesome 6 (CDN)
- **Libraries**: jQuery 3.6+, DataTables, Bootbox
- **Server**: XAMPP (Apache, MySQL, PHP)
- **Architecture**: MVC Pattern

## 📋 System Requirements

- XAMPP (Apache, MySQL, PHP)
- PHP 7.0 or higher
- MySQL 5.6 or higher
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Minimum 2GB RAM recommended

## 🚀 Installation and Setup

1. **Install XAMPP**
   - Download and install XAMPP from the official website
   - Start Apache and MySQL services

2. **Clone the project**
   ```bash
   cd c:/xampp/htdocs/
   git clone [repository-url] sistema-de-guarderia
   ```

3. **Configure the database**
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Create a new database named `sistema_guarderia`
   - Import the `sis_school.sql` file included in the project

4. **Configure permissions**
   - Ensure the `files/` folder has write permissions
   - The `files/usuarios/` folders must be writable

5. **Access the system**
   - Open your browser and go to: `http://localhost/sistema-de-guarderia`
   - Default user: admin/admin (verify in database)

## 📁 Project Structure

```
sistema-de-guarderia/
├── ajax/              # AJAX scripts for server operations
├── config/            # Database configuration
├── controllers/       # Application controllers
├── models/            # Data models
├── public/            # Public files (CSS, JS, images)
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript libraries
│   └── plugins/       # Third-party plugins
├── views/             # PHP views
│   ├── scripts/       # Specific JavaScript scripts
├── files/             # Uploaded files (images, etc.)
├── index.php          # Main entry point
├── sis_school.sql     # Database schema
└── README.md          # This file
```

## 🎯 System Features

### Administrative Panel
- **Dashboard**: General statistics dashboard
- **Academic Management**: Classroom, section, and student management
- **Attendance Control**: Student attendance tracking system
- **Medical Information**: Disease, medication, and allergy management
- **User Management**: System user administration
- **Permissions**: Access control and permissions
- **User Profile**: Personal profile editing and management

### Security
- User authentication
- Session management
- Permission validation
- Password hashing (SHA256)
- Secure file uploads

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the project
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

## 📝 Development Notes

- The system uses MVC pattern (Model-View-Controller)
- AJAX queries are handled in the `ajax/` folder
- Modern styles are in `public/css/frontend-modern.css`
- Main navigation is in `views/header.php`
- User profile management in `views/perfil.php`
- Login system with modern UI in `views/login.php`

## 📞 Support

For technical support or questions:
- Create an issue in the repository
- Contact the system administrator

## 📄 License

This project is under the MIT License. See the `LICENSE` file for more details.

---

**Developed with ❤️ to facilitate educational management in childcare centers**