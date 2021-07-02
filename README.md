# Python
Portfolio crypto: Programa desarrollado en python y php, que permite dar seguimiento a cryptoactivos, abrir, agregar, disminuir y cerrar posiciones, actualizacion de precios en intervalos de 15 minutos, estadisticas y seguimiento de un portafolio.

- Se requiere rellenar datos de chat_id y telegram token en el archivo library/config.py 
- Los archivos update_symbols.py y delete_symbols.py, se debe ejecutar como servicio una vez cada 15 minutos, para actualizar datos del portafolio.
- El archivo portfolio_crypto.sql, tiene la base de datos mysql, que debe ser importada en el servidor, y realizar las configuraciones tanto en library/config.py como en db_config.php

Este proyecto se realizó con:
- Python 3.8.10
- PHP 7.0
- mysql 8.0
- Bootstrap 3.3.7
