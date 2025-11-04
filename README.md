# Sistema de Guardería

Un sistema integral de gestión para guarderías infantiles que facilita la administración educativa y operativa. Desarrollado con tecnologías modernas para proporcionar una experiencia de usuario intuitiva y eficiente.

## ✨ Características Principales

### 👥 Gestión de Usuarios
- Registro y administración de profesores/administradores
- Sistema de permisos basado en roles
- Gestión de perfiles con imágenes
- Control de acceso seguro

### 👨‍👩‍👧‍👦 Gestión de Grupos
- Creación y organización de grupos de estudiantes
- Asignación de profesores a grupos
- Marcado de grupos favoritos

### 🔐 Sistema de Permisos
- Gestión completa de permisos del sistema
- Activación/desactivación de permisos
- Control granular de acceso

### 📊 Panel de Control
- Dashboard moderno con estadísticas
- Interfaz responsive y moderna
- Navegación intuitiva

### 📱 Interfaz Moderna
- Diseño frontend moderno con CSS personalizado
- Íconos expresivos y colores atractivos
- Layout responsive para todos los dispositivos

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7+
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 4.6.2
- **Plugins**: DataTables, Font Awesome, AdminLTE
- **Servidor**: XAMPP (Apache, MySQL, PHP)

## 📋 Requisitos del Sistema

- XAMPP (Apache, MySQL, PHP)
- PHP 7.0 o superior
- MySQL 5.6 o superior
- Navegador web moderno

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
   - Las carpetas `files/usuarios/` y `files/articulos/` deben ser escribibles

5. **Acceder al sistema**
   - Abre tu navegador y ve a: `http://localhost/sistema-de-guarderia`
   - Usuario por defecto: admin/admin (verifica en la base de datos)

## 📁 Estructura del Proyecto

```
sistema-de-guarderia/
├── ajax/              # Scripts AJAX para operaciones del servidor
├── config/            # Configuración de base de datos
├── controllers/       # Controladores de la aplicación
├── models/            # Modelos de datos
├── public/            # Archivos públicos (CSS, JS, imágenes)
├── views/             # Vistas PHP
│   ├── scripts/       # Scripts JavaScript específicos
├── files/             # Archivos subidos (imágenes, etc.)
├── index.php          # Punto de entrada principal
└── README.md          # Este archivo
```

## 🎯 Funcionalidades del Sistema

### Panel de Administración
- **Escritorio**: Dashboard con estadísticas generales
- **Grupos**: Gestión completa de grupos de estudiantes
- **Profesores**: Administración de usuarios del sistema
- **Permisos**: Control de permisos y accesos
- **Acerca de**: Información del sistema

### Seguridad
- Autenticación de usuarios
- Control de sesiones
- Validación de permisos
- Hashing de contraseñas (SHA256)

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Para contribuir:

1. Haz fork del proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📝 Notas de Desarrollo

- El sistema utiliza el patrón MVC (Modelo-Vista-Controlador)
- Las consultas AJAX se manejan en la carpeta `ajax/`
- Los estilos modernos están en `public/css/frontend-modern.css`
- La navegación principal se encuentra en `views/header.php`

## 📞 Soporte

Para soporte técnico o preguntas:
- Crea un issue en el repositorio
- Contacta al administrador del sistema

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

**Desarrollado con ❤️ para facilitar la gestión educativa en guarderías**