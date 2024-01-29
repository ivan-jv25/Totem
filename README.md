# Proyecto: Desarrollo de una Aplicación en PHP Laravel para Consumir una API

## Objetivo
El objetivo de este proyecto es crear una aplicación web utilizando PHP Laravel que consuma servicios web a través de una API proporcionada. La aplicación permitirá realizar diversas operaciones, como iniciar sesión, acceder a información de productos, familias, sub-familias, formas de pago, productos paginados y generar ventas.

## Tecnologías Utilizadas
- PHP Laravel (última versión)
- API: [Documentación de la API](https://documenter.getpostman.com/view/2994303/2s9YXe7jGf)
- Base de Datos: Formato compatible con la API
- Control de Versiones: Git
- Repositorio en GitHub: [Totem en GitHub](git@github.com:ivan-jv25/Totem.git)

## Requisitos Funcionales
1. **Login de Usuario:** Implementar un sistema de inicio de sesión para los usuarios de la aplicación.
2. **Consulta de Productos:** Permitir a los usuarios acceder a información detallada sobre los productos disponibles.
3. **Consulta de Familias y Sub-Familias:** Proporcionar acceso a la jerarquía de categorización de productos.
4. **Consulta de Formas de Pago:** Mostrar las diferentes opciones de pago disponibles.
5. **Productos Paginados:** Implementar una función de paginación para facilitar la navegación a través de una gran cantidad de productos.
6. **Generación de Ventas:** Permitir a los usuarios realizar ventas utilizando la información proporcionada por la API.

## Documentación de la API
La documentación completa de la API se encuentra disponible en el siguiente enlace: [Documentación de la API](https://documenter.getpostman.com/view/2994303/2s9YXe7jGf)

## Estructura de la Base de Datos
La base de datos de la aplicación seguirá el mismo formato que la API para garantizar la coherencia en los datos y su compatibilidad.

## Uso de Git
El proyecto se gestionará utilizando Git para el control de versiones. A continuación, se detallan los pasos para enlazar el repositorio de GitHub al proyecto local:

1. **Clonar el Repositorio:**
   Para comenzar, clona el repositorio desde GitHub utilizando el siguiente comando en tu terminal:
   	```
	git clone git@github.com:ivan-jv25/Totem.git
    cd tu-proyecto
   ```


2. **Enlazar el Repositorio Remoto:**
Una vez que hayas realizado cambios en el proyecto de forma local y desees enviarlos al repositorio en GitHub, sigue estos pasos:
   	```
    git add .
    git commit -m "Mensaje descriptivo de los cambios realizados"
    git push origin master
   ```

Esto enviará tus cambios al repositorio remoto en GitHub, específicamente a la rama principal (master).

## Consideraciones Adicionales
- La aplicación deberá ser desarrollada siguiendo las mejores prácticas de Laravel y PHP.
- Se deberá implementar la lógica necesaria para manejar adecuadamente los errores y excepciones que puedan surgir durante el consumo de la API.
- Se recomienda realizar pruebas exhaustivas para garantizar el correcto funcionamiento de todas las funcionalidades implementadas.
