# Generador automático de WSDL
Ejemplo de cómo utilizar un generador automático de WSDL 


## Enlace a la presentación realizada para este trabajo 
[Precentacion de WSDL](https://docs.google.com/presentation/d/1z3TiOAEY21skQvx4Ac_EO5tHQX8LjuPhd55YC68ptsY/edit?usp=sharing)

Sigan los pasos de la presentación y utilicen los archivos de este repo. 

creamos una Base de datos llamada Productos, con el sql que tiene este repo.
vamos al archivo /config/bd.php
y en el construct ponemos nuestra info.

```markdown
function __construct() {
        $this->host = "localhost";
        $this->user = "root"; // nuestro usuario
        $this->pass = ""; // nuestra pass
        $this->basedatos = "Productos"; // el nombre de la base de datos creada
        

```
