# PEQUE CONTROL - Sistema de Gestión de Guardería

Un sistema integral de gestión para guarderías infantiles que facilita la administración educativa y operativa. Desarrollado con tecnologías modernas para proporcionar una experiencia de usuario intuitiva y eficiente.

## ✨ Características Principales

### 👥 Gestión de Usuarios
- Registro y administración de maestros/administradores
- Sistema de permisos basado en roles
- Gestión de perfiles con imágenes
- Control de acceso seguro
- Roles especializados (Administrador, Médico/Enfermería, Profesor)

### 👨‍👩‍👧‍👦 Gestión Académica
- Gestión de aulas y secciones
- Registro y seguimiento de estudiantes
- Sistema de control de asistencia
- Asignación de adultos responsables
- Gestión de horarios de clase
  - Asignación de horarios por niño y día de la semana
  - Filtros avanzados por nombre, aula y sección
  - Vista diferenciada por roles (Admin/Profesor vs Tutor)
  - Control de permisos granular

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
- Sistema de permisos médicos especializados
- Página de información del sistema con diseño de tarjetas

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7+
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 4.6.2
- **Íconos**: Font Awesome 6 (CDN)
- **Librerías**: jQuery 3.6+, DataTables, Bootbox
- **Servidor**: XAMPP (Apache, MySQL, PHP)
- **Arquitectura**: Patrón MVC

## 📋 Requisitos del Sistema

- XAMPP (Apache, MySQL, PHP)
- PHP 7.0 o superior
- MySQL 5.6 o superior
- Navegador web moderno (Chrome, Firefox, Safari, Edge)
- Mínimo 2GB RAM recomendado

## 🚀 Instalación y Configuración

1. **Instalar XAMPP**
   - Descarga e instala XAMPP desde el sitio oficial
   - Inicia los servicios Apache y MySQL

2. **Clonar el proyecto**
   ```bash
   cd c:/xampp/htdocs/
   git clone [url-del-repositorio] sistema-de-guarderia
   ```

3. **Configurar la base de datos**
   - Abre phpMyAdmin en `http://localhost/phpmyadmin`
   - Crea una nueva base de datos llamada `sistema_guarderia`
   - Importa el archivo `sis_school.sql` incluido en el proyecto

4. **Configurar permisos**
   - Asegúrate de que la carpeta `files/` tenga permisos de escritura
   - Las carpetas `files/usuarios/` deben ser escribibles

5. **Acceder al sistema**
   - Abre tu navegador y ve a: `http://localhost/sistema-de-guarderia`
   - Usuario por defecto: admin/admin (verifica en la base de datos)

## 📁 Project Structure

```
sistema-de-guarderia/
├── ajax/              # Scripts AJAX para operaciones del servidor
│   ├── horarios.php   # AJAX para gestión de horarios (Nuevo)
├── config/            # Configuración de base de datos
├── controllers/       # Controladores de la aplicación
│   ├── HorariosController.php  # Controlador de horarios (Nuevo)
├── models/            # Modelos de datos
│   ├── Horarios.php   # Modelo de horarios (Nuevo)
├── public/            # Archivos públicos (CSS, JS, imágenes)
│   ├── css/           # Hojas de estilo
│   ├── js/            # Librerías JavaScript
│   └── plugins/       # Plugins de terceros
├── views/             # Vistas PHP
│   ├── horarios.php   # Vista de gestión de horarios
│   ├── scripts/       # Scripts JavaScript específicos
│       ├── horarios.js  # JavaScript para horarios
│   ├── styles/        # Hojas de estilo específicas
│       ├── login.css     # Estilos para página de login
│       ├── landing.css   # Estilos para página de inicio
│       ├── acerca.css    # Estilos para página Acerca
├── files/             # Archivos subidos (imágenes, etc.)
├── index.php          # Punto de entrada principal
├── sis_school.sql     # Esquema de base de datos
└── README.md          # Este archivo
```

## 🎯 Funcionalidades del Sistema

### Panel Administrativo
- **Escritorio**: Dashboard con estadísticas generales
- **Gestión Académica**: Administración de aulas, secciones y estudiantes
- **Horarios de Estudio**: Gestión completa de horarios de clase
- **Control de Asistencia**: Sistema de seguimiento de asistencia estudiantil
- **Información Médica**: Gestión de enfermedades, medicamentos y alergias
- **Permisos de Ausencia**: Gestión de permisos médicos y personales
- **Gestión de Usuarios**: Administración de usuarios del sistema
- **Permisos**: Control de acceso y permisos
- **Perfil de Usuario**: Edición y gestión de perfil personal
- **Acerca del Sistema**: Información completa del sistema con diseño moderno

### Seguridad
- Autenticación de usuarios
- Gestión de sesiones
- Validación de permisos
- Hashing de contraseñas (SHA256)
- Subidas de archivos seguras

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Para contribuir:

1. Haz fork del proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📝 Development Notes

- El sistema utiliza el patrón MVC (Modelo-Vista-Controlador)
- Las consultas AJAX se manejan en la carpeta `ajax/`
- Los estilos modernos están en `public/css/frontend-modern.css`
- La navegación principal se encuentra en `views/header.php`
- Gestión de perfil de usuario en `views/perfil.php`
- Sistema de login con UI moderna en `views/login.php`
- Funcionalidad de horarios implementada con filtros avanzados y control de permisos por rol
- Sistema de permisos médicos especializados para usuarios Médico/Enfermería
- Página Acerca del Sistema con diseño moderno de tarjetas

## 📞 Soporte

Para soporte técnico o preguntas:
- Crea un issue en el repositorio
- Contacta al administrador del sistema

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

**Desarrollado con ❤️ para facilitar la gestión educativa en guarderías**