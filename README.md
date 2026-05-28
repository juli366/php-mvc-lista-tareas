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

**Opción A — XAMPP (recomendado):**
1. Descarga e instala [XAMPP](https://www.apachefriends.org/)
2. SQLite y `pdo_sqlite` ya vienen habilitados — no se requiere configuración adicional

**Opción B — PHP standalone:**
1. Descarga el zip de [windows.php.net](https://windows.php.net/download/) (elige *Thread Safe*)
2. Descomprime y agrega la carpeta al `PATH` del sistema
3. En la carpeta de PHP, copia `php.ini-development` → `php.ini`
4. Abre `php.ini` y descomenta estas líneas (quita el `;` inicial):
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   ```
5. Verifica con `php -m | findstr sqlite`

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
