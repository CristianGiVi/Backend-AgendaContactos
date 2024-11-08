# Agenda de Contactos

Agenda de Contactos es una aplicación web de backend desarrollada en PHP que permite gestionar contactos, 
incluyendo su nombre, número de identificación (cédula), teléfonos celulares y correos electrónicos. 
Utiliza una arquitectura de repositorios para la interacción con la base de datos MySQL.

## Funcionalidades Actuales

- Registro de nuevos contactos con nombre y número de identificación.
- Gestión de contactos: obtener todos los contactos, obtener uno específico, editar y eliminar.
- Soporte para múltiples números de teléfono y correos electrónicos por contacto.
- Validaciones para asegurarse de que los datos de contacto sean válidos y que los números de identificación no se repitan.
- Respuesta eficiente en formato JSON para operaciones CRUD.
- Implementación de CORS para permitir el acceso desde diferentes orígenes.

## Funcionalidades Futuras

- Implementación de autenticación y autorización de usuarios.
- Mejoras en la validación de datos y manejo de errores.

## Tecnologías Utilizadas

- PHP: Lenguaje de programación utilizado para desarrollar el backend.
- MySQL: Sistema de gestión de bases de datos utilizado para almacenar la información de los contactos.
- CORS (Cross-Origin Resource Sharing): Implementado para permitir solicitudes desde diferentes orígenes.
- JSON: Formato utilizado para la comunicación entre el servidor y el cliente.

## Instalación y Uso

Requisitos previos:

- PHP 7.4 o superior.
- Servidor web (Apache o Nginx).
- Base de datos MySQL o MariaDB.

1. Clona el repositorio en tu máquina local.

2. Configura tu base de datos en MySQL:

- Crea una base de datos llamada agenda_contactos.
  
- Crea las tablas necesarias para almacenar los contactos, teléfonos y correos electrónicos. 
  
3. Configura tu servidor para que apunte al directorio donde clonaste el repositorio.

4. Asegúrate de que tu servidor tenga habilitada la extensión mysqli de PHP.

5. Inicia el servidor y navega a la ruta del proyecto para probar las funcionalidades.

## Contribución

Si deseas contribuir a este proyecto, sigue estos pasos:

- Haz un fork del repositorio.
- Crea una nueva rama (git checkout -b nueva-funcionalidad).
- Realiza tus cambios y haz commits (git commit -am 'Agrega nueva funcionalidad').
- Haz push a la rama (git push origin nueva-funcionalidad).
- Crea un nuevo Pull Request.

## Autores

- Cristian Giraldo Villegas [CristianGiVi](https://github.com/CristianGiVi)
