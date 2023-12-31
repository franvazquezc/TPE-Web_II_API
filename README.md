# TPE-Web_II_APIrest
## Integrantes: 
  - Francisco Vazquez: franvazquezcarrasco@gmail.com
## Manejo de coleccion de productos mediante API rest: 
### Obtencion de producto/s en formato JSON (GET):
|Función				| Verbo HTTP | URI                   |
|---------------------------------------|------------|-----------------------|
|Obtener un producto por id		| GET        | /product/id           |
|Obtener listado total de productos	| GET        | /products             |

A este último se le puede agregar la combinación de una serie de parametros:
- Ordenar:	

		?sort=<campo>&order=<ASC> :			
			Ordena en forma ascendente por el campo indicado.

		?sort=<campo>&order=<DESC> :			
			Ordena en forma descendente por el campo indicado.

- Filtrar:

		?search_by=<campo>&sought_value=<valor>	:	
			Filtra los resultados que contengan el valor dado un campo determinado.

- Paginado:

		?page=<nro_de_pagina>&page_size=<tamaño_de_pagina> :	
			Divide los resultados en paginas de un tamaño determinado y devuelve la pagina indicada.

Deben usarse por pares (si se usa un parametro del par se esta obligado a usar el otro) y pueden combinarse en una misma consulta.

### Borrar un producto (DELETE):
|Función				| Verbo HTTP | URI                   |
|---------------------------------------|------------|-----------------------|
|Borrar un producto con un id dado	| DELETE     | /product/id           |

### Insertar un producto (ADD):
|Función				| Verbo HTTP | URI                   |
|---------------------------------------|------------|-----------------------|
|Insertar un producto en la BBDD	| POST	     | /product              |

En el body de la request deben especificarse los valores de los campos del producto a agregar en formato JSON:
```json
{
        "name": "Nombre del producto",				//varcha
        "description": "Descripción del producto",		//text
        "price": "precio_del_producto",				//double
	"stock": "stock_del_producto",				//int
	"category_id": "id_de_la_categoria_del_producto"	//int
}
```
Todos los campos deben estar completados.
### Modificar un producto (UPDATE):
|Función				| Verbo HTTP | URI                   |
|---------------------------------------|------------|-----------------------|
|Modificar un producto con un id dado	| PUT	     | /product/id           |

En el body de la request deben especificarse los valores de los campos del producto a modificar en formato JSON:
```json		
{
        "name": "Nombre del producto",				//varchar
        "description": "Descripción del producto",		//text
        "price": "precio_del_producto",				//double
        "stock": "stock_del_producto",				//int
	"category_id": "id_de_la_categoria_del_producto"	//int
}

```
Todos los campos deben estar completados con los nuevos valores.

## DER:
![Diagrama de la BBDD](https://github.com/franvazquezc/TPE-Web_II/blob/main/DER_TPE_Web_II.jpg)