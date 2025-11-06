-- ============================================
-- BASE DE DATOS: Sistema de Guardería - PequeControl 
-- Descripción: Diseño completo para MySQL/phpMyAdmin
-- ============================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sis_school CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base creada
USE sis_school;

-- ============================================
-- TABLA: roles
-- ============================================
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: estados_usuario (NUEVA)
-- ============================================
CREATE TABLE estados_usuario (
    id_estado_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
) ENGINE=InnoDB;

-- ============================================
-- TABLA: usuarios (ACTUALIZADA)
-- ============================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    telefono VARCHAR(15),
    direccion TEXT,
    estado_usuario_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id_rol)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (estado_usuario_id) REFERENCES estados_usuario(id_estado_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: aulas
-- ============================================
CREATE TABLE aulas (
    id_aula INT AUTO_INCREMENT PRIMARY KEY,
    nombre_aula VARCHAR(50) NOT NULL,
    descripcion TEXT
) ENGINE=InnoDB;

-- ============================================
-- TABLA: secciones
-- ============================================
CREATE TABLE secciones (
    id_seccion INT AUTO_INCREMENT PRIMARY KEY,
    nombre_seccion VARCHAR(50) NOT NULL,
    aula_id INT,
    FOREIGN KEY (aula_id) REFERENCES aulas(id_aula)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: niños
-- ============================================
CREATE TABLE ninos (
    id_nino INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    edad INT,
    peso DECIMAL(5,2),
    aula_id INT,
    seccion_id INT,
    maestro_id INT,
    tutor_id INT,
    estado BOOLEAN DEFAULT 1,
    FOREIGN KEY (aula_id) REFERENCES aulas(id_aula)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (seccion_id) REFERENCES secciones(id_seccion)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (maestro_id) REFERENCES usuarios(id_usuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (tutor_id) REFERENCES usuarios(id_usuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: enfermedades
-- ============================================
CREATE TABLE enfermedades (
    id_enfermedad INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    nombre_enfermedad VARCHAR(100),
    descripcion TEXT,
    fecha_diagnostico DATE,
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: medicamentos
-- ============================================
CREATE TABLE medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    nombre_medicamento VARCHAR(100),
    dosis VARCHAR(100),
    frecuencia VARCHAR(100),
    observaciones TEXT,
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: alergias
-- ============================================
CREATE TABLE alergias (
    id_alergia INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    tipo_alergia VARCHAR(100),
    descripcion TEXT,
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: responsables_retiro
-- ============================================
CREATE TABLE responsables_retiro (
    id_responsable INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    nombre_completo VARCHAR(100),
    parentesco VARCHAR(50),
    telefono VARCHAR(15),
    autorizacion_firma VARCHAR(255),
    periodo_inicio DATE,
    periodo_fin DATE,
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: estados_asistencia
-- ============================================
CREATE TABLE estados_asistencia (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
) ENGINE=InnoDB;

-- ============================================
-- TABLA: asistencias
-- ============================================
CREATE TABLE asistencias (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    fecha DATE NOT NULL,
    estado_id INT NOT NULL,
    observaciones TEXT,
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (estado_id) REFERENCES estados_asistencia(id_estado)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: permisos_ausencia
-- ============================================
CREATE TABLE permisos_ausencia (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    tipo_permiso ENUM('Médico', 'Personal', 'Otro') NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    hora_inicio TIME,
    hora_fin TIME,
    archivo_permiso VARCHAR(255),
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: alertas
-- ============================================
CREATE TABLE alertas (
    id_alerta INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    fecha_alerta DATETIME DEFAULT CURRENT_TIMESTAMP,
    mensaje TEXT,
    tipo ENUM('Inasistencia', 'Emergencia', 'Salud', 'Otro', 'Conducta', 'Evaluación', 'Desarrollo'),
    estado ENUM('Pendiente', 'Respondida') DEFAULT 'Pendiente',
    FOREIGN KEY (id_nino) REFERENCES ninos(id_nino)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- DATOS DE EJEMPLO ACTUALIZADOS
-- ============================================

-- Estados de usuario (NUEVA SECCIÓN)
INSERT INTO estados_usuario (nombre_estado, descripcion) VALUES 
('Activo', 'Usuario con acceso completo al sistema'),
('Inactivo', 'Usuario temporalmente bloqueado'),
('Suspendido', 'Usuario suspendido por algún motivo'),
('Vacaciones', 'Usuario en período de vacaciones'),
('Licencia', 'Usuario con licencia temporal');

-- Roles
INSERT INTO roles (nombre_rol) VALUES 
('Padre/Tutor'), 
('Maestro'), 
('Administrador'), 
('Médico/Enfermería');

-- Usuarios (ACTUALIZADA CON HASHES SHA256)
INSERT INTO usuarios (nombre_completo, email, password, rol_id, telefono, direccion, estado_usuario_id) VALUES
('Ana López', 'ana.lopez@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, '7777-1111', 'Col. Miramonte, San Salvador', 1),
('Luis Martínez', 'luis.martinez@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, '7777-2222', 'San Salvador', 1),
('José Aquino', 'jose.aquino@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 3, '7777-3333', 'Santa Tecla', 1),
('Lic. Elena Rivas', 'elena.rivas@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 4, '7777-4444', 'Antiguo Cuscatlán', 1),
('María González', 'maria.gonzalez@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, '7777-5555', 'San Salvador', 2);

-- Aulas
INSERT INTO aulas (nombre_aula, descripcion) VALUES 
('Prekínder', 'Aula destinada a niños de 3 a 4 años'),
('Kínder', 'Aula destinada a niños de 4 a 5 años'),
('Transition', 'Aula de transición de 5 a 6 años');

-- Secciones
INSERT INTO secciones (nombre_seccion, aula_id) VALUES 
('Prekínder A', 1),
('Prekínder B', 1),
('Kínder A', 2),
('Transition A', 3);

-- Niños
INSERT INTO ninos (nombre_completo, fecha_nacimiento, edad, peso, aula_id, seccion_id, maestro_id, tutor_id) VALUES 
('Carlos López', '2021-06-12', 4, 16.5, 1, 1, 2, 1),
('Ana García', '2020-09-15', 5, 18.2, 2, 3, 2, 1),
('Miguel Rodríguez', '2021-02-20', 4, 17.1, 1, 2, 2, 1),
('Sofia Hernández', '2019-11-10', 6, 20.3, 3, 4, 5, 1);

-- Enfermedades
INSERT INTO enfermedades (id_nino, nombre_enfermedad, descripcion, fecha_diagnostico) VALUES 
(1, 'Asma', 'Leve, controlada con inhalador', '2023-09-10'),
(2, 'Rinitis alérgica', 'Alérgica estacional', '2024-01-15');

-- Medicamentos
INSERT INTO medicamentos (id_nino, nombre_medicamento, dosis, frecuencia, observaciones) VALUES 
(1, 'Salbutamol', '2 inhalaciones', 'Cada 8 horas', 'Solo en caso de crisis'),
(2, 'Antihistamínico', '5ml', 'Una vez al día', 'Durante época de alergias');

-- Alergias
INSERT INTO alergias (id_nino, tipo_alergia, descripcion) VALUES 
(1, 'Polvo', 'Reacción leve al polvo y al humo'),
(2, 'Polen', 'Alergia estacional al polen'),
(3, 'Maní', 'Alergia severa, evitar completamente');

-- Responsables de retiro
INSERT INTO responsables_retiro (id_nino, nombre_completo, parentesco, telefono, autorizacion_firma, periodo_inicio, periodo_fin) VALUES 
(1, 'María Pérez', 'Tía', '7777-9999', 'firma_maria.png', '2025-01-01', '2025-12-31'),
(1, 'Pedro López', 'Abuelo', '7777-8888', 'firma_pedro.png', '2025-01-01', '2025-12-31'),
(2, 'Juan García', 'Papá', '7777-7777', 'firma_juan.png', '2025-01-01', '2025-12-31');

-- Estados de asistencia
INSERT INTO estados_asistencia (nombre_estado, descripcion) VALUES 
('Asistió', 'El niño asistió normalmente'),
('Inasistencia', 'El niño no asistió'),
('Permiso', 'Ausencia con permiso autorizado'),
('Tardanza', 'El niño llegó tarde'),
('Salida temprana', 'El niño se retiró temprano');

-- Asistencias
INSERT INTO asistencias (id_nino, fecha, estado_id, observaciones) VALUES 
(1, '2025-11-04', 1, 'Ninguna novedad'),
(2, '2025-11-04', 1, 'Participó activamente'),
(3, '2025-11-04', 4, 'Llegó 10 minutos tarde'),
(1, '2025-11-05', 2, 'No asistió por enfermedad');

-- Permisos de ausencia
INSERT INTO permisos_ausencia (id_nino, tipo_permiso, descripcion, fecha_inicio, fecha_fin, hora_inicio, hora_fin, archivo_permiso) VALUES 
(1, 'Médico', 'Consulta pediátrica programada', '2025-11-10', '2025-11-10', '08:00:00', '12:00:00', 'permiso_carlos.pdf'),
(2, 'Personal', 'Cita familiar', '2025-11-12', '2025-11-12', '14:00:00', '16:00:00', 'permiso_ana.pdf');

-- Alertas
INSERT INTO alertas (id_nino, mensaje, tipo, estado) VALUES 
(1, 'El niño no asistió el día 2025-11-03', 'Inasistencia', 'Pendiente'),
(3, 'Tendencia a comportamiento agresivo con otros niños', 'Conducta', 'Respondida'),
(4, 'Excelente progreso en habilidades sociales', 'Desarrollo', 'Pendiente'),
(1, 'Recordatorio: Traer inhalador de rescate', 'Salud', 'Respondida');