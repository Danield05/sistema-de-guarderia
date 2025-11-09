# PEQUE CONTROL - Sistema de Gestión de Guardería

Un sistema integral de gestión para guarderías infantiles que facilita la administración educativa y operativa. Desarrollado con tecnologías modernas para proporcionar una experiencia de usuario intuitiva y eficiente.

## ✨ Key Features

### 👥 Gestión de Usuarios
- Registro y administración de maestros/administradores
- Sistema de permisos basado en roles
- Gestión de perfiles con imágenes
- Control de acceso seguro

### 👨‍👩‍👧‍👦 Gestión Académica
- Gestión de aulas y secciones
- Registro y seguimiento de estudiantes
- Sistema de control de asistencia
- Asignación de adultos responsables

### 🏥 Información Médica
- Seguimiento y gestión de enfermedades
- Registros de administración de medicamentos
- Sistema de gestión de alergias
- Registro de consultas médicas

### 🔐 Sistema de Permisos
- Gestión completa de permisos del sistema
- Activación/desactivación de permisos
- Control granular de acceso

### 📊 Panel de Control
- Dashboard moderno con estadísticas
- Interfaz responsiva y moderna
- Navegación intuitiva

### 👤 Perfil de Usuario
- Edición de perfil personal
- Funcionalidad de cambio de contraseña
- Subida de imagen de perfil
- Visualización de información de cuenta

### 📱 Interfaz Moderna
- Diseño frontend moderno con CSS personalizado
- Íconos expresivos y colores atractivos
- Layout responsivo para todos los dispositivos
- Efectos glassmorphism y animaciones

## 🛠️ Technologies Used

- **Backend**: PHP 7+
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 4.6.2
- **Íconos**: Font Awesome 6 (CDN)
- **Librerías**: jQuery 3.6+, DataTables, Bootbox
- **Servidor**: XAMPP (Apache, MySQL, PHP)
- **Arquitectura**: Patrón MVC

## 📋 System Requirements

- XAMPP (Apache, MySQL, PHP)
- PHP 7.0 o superior
- MySQL 5.6 o superior
- Navegador web moderno (Chrome, Firefox, Safari, Edge)
- Mínimo 2GB RAM recomendado

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

4. **Configurar permisos**
   - Asegúrate de que la carpeta `files/` tenga permisos de escritura
   - Las carpetas `files/usuarios/` deben ser escribibles

5. **Access the system**
   - Open your browser and go to: `http://localhost/sistema-de-guarderia`
   - Default user: admin/admin (verify in database)

## 📁 Project Structure

```
sistema-de-guarderia/
├── ajax/              # Scripts AJAX para operaciones del servidor
├── config/            # Configuración de base de datos
├── controllers/       # Controladores de la aplicación
├── models/            # Modelos de datos
├── public/            # Archivos públicos (CSS, JS, imágenes)
│   ├── css/           # Hojas de estilo
│   ├── js/            # Librerías JavaScript
│   └── plugins/       # Plugins de terceros
├── views/             # Vistas PHP
│   ├── scripts/       # Scripts JavaScript específicos
├── files/             # Archivos subidos (imágenes, etc.)
├── index.php          # Punto de entrada principal
├── sis_school.sql     # Esquema de base de datos
└── README.md          # Este archivo
```

## 🎯 System Features

### Panel Administrativo
- **Escritorio**: Dashboard con estadísticas generales
- **Gestión Académica**: Administración de aulas, secciones y estudiantes
- **Control de Asistencia**: Sistema de seguimiento de asistencia estudiantil
- **Información Médica**: Gestión de enfermedades, medicamentos y alergias
- **Gestión de Usuarios**: Administración de usuarios del sistema
- **Permisos**: Control de acceso y permisos
- **Perfil de Usuario**: Edición y gestión de perfil personal

### Seguridad
- Autenticación de usuarios
- Gestión de sesiones
- Validación de permisos
- Hashing de contraseñas (SHA256)
- Subidas de archivos seguras

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Para contribuir:

1. Fork the project
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

## 📝 Development Notes

- El sistema utiliza el patrón MVC (Modelo-Vista-Controlador)
- Las consultas AJAX se manejan en la carpeta `ajax/`
- Los estilos modernos están en `public/css/frontend-modern.css`
- La navegación principal se encuentra en `views/header.php`
- Gestión de perfil de usuario en `views/perfil.php`
- Sistema de login con UI moderna en `views/login.php`

## 📞 Support

For technical support or questions:
- Create an issue in the repository
- Contact the system administrator

## 📄 License

This project is under the MIT License. See the `LICENSE` file for more details.

---

**Developed with ❤️ to facilitate educational management in childcare centers**