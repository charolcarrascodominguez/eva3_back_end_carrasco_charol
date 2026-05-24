# ProviEmplea API
**Autor:** Charol Carrasco  
**Asignatura:** Desarrollo Backend  
**Evaluación:** U3 - Desarrollo Backend  
**Tecnología:** PHP 8.3 - Laravel 13 - MySQL 8.4.3 - Docker  
**Formato de datos:** JSON  

---

# Descripción del Proyecto

ProviEmplea es una plataforma de búsqueda inversa de empleo desarrollada para la Municipalidad de Providencia.

La plataforma permite:

- Registrar talentos (Personas)
- Registrar empresas
- Gestionar solicitudes de contacto
- Administrar procesos de selección
- Visualizar estadísticas
- Documentar la API mediante Swagger (OpenAPI)

El sistema implementa arquitectura RESTful usando Laravel.

---

# Arquitectura del Sistema

Cliente (Frontend)  
⬇  
API REST (Laravel)  
⬇  
Base de datos MySQL (Docker)  

Patrón aplicado:

- MVC
- API REST
- UUID como clave primaria
- JSON como formato único
- Soft delete lógico (activo = false)

---

# Instalación y Configuración

## Clonar repositorio

git clone <repositorio>
cd proviemplea

#Levantar contenedores Docker

docker compose up -d --build
#Instalar dependencias

docker compose exec app composer install
#Generar clave de aplicación

docker compose exec app php artisan key:generate

#Ejecutar migraciones

docker compose exec app php artisan migrate

#Acceder al sistema
Abrir navegador:

http://localhost:8081


# Modelo de Datos
# Entidades
#1. Personas
id (UUID)
email
telefono
codigo_talento
nivel_educacional
titulo_carrera
anios_experiencia
competencias (JSON)
tipo_jornada
modalidad
validado
activo

#2. Empresas
id (UUID)
nombre_empresa
rut_empresa
email
tipo_empresa
rubro
beneficios (JSON)
validado
activo

#3. ContactosSolicitados
id (UUID)
empresa_id (FK)
persona_id (FK)
estado
notas_admin
fechas del proceso
# Operaciones CRUD Implementadas
# Personas
Método	Endpoint	Descripción
GET	/api/personas	Listar personas activas
POST	/api/personas	Crear persona
GET	/api/personas/{id}	Obtener persona
PUT	/api/personas/{id}	Actualizar persona
DELETE	/api/personas/{id}	Desactivar persona
PATCH	/api/personas/{id}/validar	Validar perfil

# Empresas
Método	Endpoint	Descripción
GET	/api/empresas	Listar empresas
POST	/api/empresas	Crear empresa
GET	/api/empresas/{id}	Obtener empresa
PUT	/api/empresas/{id}	Actualizar empresa
DELETE	/api/empresas/{id}	Desactivar empresa
PATCH	/api/empresas/{id}/validar	Validar empresa

# Administración
Método	Endpoint	Descripción
GET	/api/admin/contactos	Listar contactos
POST	/api/admin/contactos	Crear contacto
PATCH	/api/admin/contactos/{id}/estado	Actualizar estado
GET	/api/admin/estadisticas	Ver estadísticas

# Documentación Swagger (OpenAPI)
Instalación


docker compose exec app composer require darkaonline/l5-swagger
docker compose exec app php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
Generar documentación


docker compose exec app php artisan l5-swagger:generate

#Acceder a Swagger UI

http://localhost:8080/api/documentation
