<?php
require_once 'model.php';

class ProductModel extends Model {

    function getAttributes() {
        $query = $this->db->prepare("SELECT * FROM products LIMIT 1");
        $query->execute();

        $columns = [];
        for ($i = 0; $i < $query->columnCount(); $i++) {
            $column = $query->getColumnMeta($i);
            array_push($columns, $column['name']);
        }

        return $columns;
    }

    public function get($queryParams) {
        // Sentencia SQL base (trae todas las entidades de la tabla products)
        $sql = 'SELECT * FROM products';
        
        //----------getProduct by Id----------
        if(isset($queryParams[':ID'])) {
            $sql .= ' WHERE product_id = :ID';
            
            $query = $this->db->prepare($sql);
            $query->bindParam(':ID', $queryParams[':ID']);
            $query->execute();

            $product = $query->fetch(PDO::FETCH_OBJ);

            return $product;
        }

        //----------Chequea que solo haya parametros permitidos en la URL----------
        $allowedParams = ['resource', 'search_by', 'sought_value', 'sort', 'order', 'page', 'page_size'];
        $illegalParam = false;

        foreach(array_keys($queryParams) as $key) {
            if (!in_array($key, $allowedParams)) {
                $illegalParam = true;
                break;
            }
        }
        if($illegalParam) {
            return null;
        }
        
        //----------Filtrar busqueda por campo----------
        if(isset($queryParams['search_by']) && isset($queryParams['sought_value'])) {
            
            $productAttributes = $this->getAttributes();
            if(in_array($queryParams['search_by'], $productAttributes)) {
                $sql .= ' WHERE ' . $_GET['search_by'] . ' = :sought_value';
            } else {
                return null;
            }
        }
        //----------Busqueda ordenada----------
        if(isset($queryParams['sort']) && isset($queryParams['order'])) {
            
            $productAttributes = $this->getAttributes();
            if(($_GET['order'] == "DESC" || $_GET['order'] == "ASC") &&
                in_array($_GET['sort'], $productAttributes)) {
                
                $sql .= ' ORDER BY ' . $queryParams['sort'] . ' ' . $queryParams['order'];

            } else {
                return null;
            }
        }
        //----------Paginado----------
        if(isset($queryParams['page']) && isset($queryParams['page_size'])) {
            // Si el nro de pagina y el tamaño de la misma son enteros positivos
            if(is_numeric($queryParams['page']) && is_int($queryParams['page'] + 0) && (int)$queryParams['page'] > 0
                && is_numeric($queryParams['page_size']) && is_int($queryParams['page_size'] + 0) && (int)$queryParams['page_size'] > 0){
            
                $offset = ($queryParams['page'] - 1) * $queryParams['page_size'];

                // Se agrega a la sentencia sql
                $sql .= ' LIMIT :pageSize OFFSET :offset';
            } else {
                return null;
            }
        }
        //----------Ejecucion de la consulta----------
        
        $query = $this->db->prepare($sql);
        
        // Se setean los parametros bindeados (si los hay)
        if(isset($queryParams['page']) && isset($queryParams['page_size'])) {
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':pageSize', $queryParams['page_size'], PDO::PARAM_INT);
        }
        if(isset($queryParams['sought_value'])) {
            $query->bindParam(':sought_value', $queryParams['sought_value']);
        }
        
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_OBJ);

        return $products;
    }

    function insert($name, $description, $price, $stock, $category_id) {
        $query = $this->db->prepare('INSERT INTO products (name, description, price, stock, category_id) VALUES(?,?,?,?,?)');
        $query->execute([$name, $description, $price, $stock, $category_id]);

        return $this->db->lastInsertId();
    }

    function delete($id) {
        $query = $this->db->prepare('DELETE FROM products WHERE product_id = ?');
        $query->execute([$id]);
    }

    function update($name, $description, $price, $stock, $category_id, $id) {
        $query = $this->db->prepare('UPDATE products 
                                    SET name = ?, description = ?, price = ?, stock = ?, category_id = ? 
                                    WHERE product_id = ?');
        $query->execute([$name, $description, $price, $stock, $category_id, $id]);
    }
}