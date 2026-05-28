# Lista de Tareas Kanban — PHP MVC

Tablero Kanban para gestionar tareas con tres columnas de estado (Pendiente, En Progreso, Completada) y arrastre entre columnas en tiempo real.

## Características

- Tablero Kanban con columnas: Pendiente / En Progreso / Completada
- Arrastrar y soltar tarjetas entre columnas (SortableJS)
- Contadores de tareas en tiempo real por estado
- Creación y eliminación de tareas
- Persistencia en SQLite

## Stack

- PHP 8.x (sin frameworks)
- Arquitectura MVC
- Bootstrap 5.3 + Bootstrap Icons (CDN)
- SortableJS (CDN) para drag & drop
- SQLite vía PDO

---

## Requisitos

### PHP 8.x + extensión pdo_sqlite

#### Windows
1. Descarga [XAMPP](https://www.apachefriends.org/) — incluye PHP y SQLite
2. Con PHP standalone: descarga desde [windows.php.net](https://windows.php.net/download/) y habilita `extension=pdo_sqlite` en `php.ini`

#### Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install php8.3 php8.3-sqlite3 -y
php --version
```

#### macOS
```bash
brew install php
php --version
# pdo_sqlite viene incluido con la instalación de Homebrew
```

---

## Instalación y ejecución

```bash
# 1. Clona el repositorio
git clone https://github.com/juli366/php-mvc-lista-tareas.git
cd php-mvc-lista-tareas

# 2. Crea el directorio de base de datos
mkdir -p db

# 3. Inicia el servidor de desarrollo
php -S localhost:8004

# 4. Abre en el navegador
# http://localhost:8004
```

> **Nota:** La base de datos SQLite (`db/tareas.sqlite`) se crea automáticamente al primer acceso.

## Estructura del proyecto

```
php-mvc-lista-tareas/
├── index.php                  # Front controller / router
├── controllers/
│   └── TareasController.php
├── models/
│   └── TareaModel.php
├── db/                        # SQLite (ignorado por git)
└── views/
    ├── layout/
    │   ├── header.php
    │   └── footer.php
    └── tareas/
        └── index.php
```
