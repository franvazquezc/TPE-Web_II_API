<?php
require_once 'model.php';

class ProductModel extends Model {

    public function getAttributes() {
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
        
        //----------Filtrar busqueda por campo----------
        
        if(isset($queryParams['search_by']) && isset($queryParams['sought_value'])) {
            $sql .= ' WHERE ' . $_GET['search_by'] . ' = :sought_value';
        }

        //----------Busqueda ordenada----------
        
        if(isset($queryParams['sort']) && isset($queryParams['order'])) {
            $sql .= ' ORDER BY ' . $queryParams['sort'] . ' ' . $queryParams['order'];
        }
        
        //----------Paginado----------
        
        if(isset($queryParams['page']) && isset($queryParams['page_size'])) {
            $offset = ($queryParams['page'] - 1) * $queryParams['page_size'];
            $sql .= ' LIMIT :pageSize OFFSET :offset';
        }
        
        //----------Ejecucion de la consulta----------
        
        // Preparación
        $query = $this->db->prepare($sql);

        // Seteo de los parametros bindeados (si los hay)
        if(isset($queryParams['page']) && isset($queryParams['page_size'])) {
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':pageSize', $queryParams['page_size'], PDO::PARAM_INT);
        }
        if(isset($queryParams['sought_value'])) {
            $query->bindParam(':sought_value', $queryParams['sought_value']);
        }
        // Ejecución
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_OBJ);

        return $products;
    }

    public function insert($name, $description, $price, $stock, $category_id) {
        $query = $this->db->prepare('INSERT INTO products (name, description, price, stock, category_id) VALUES(?,?,?,?,?)');
        $query->execute([$name, $description, $price, $stock, $category_id]);

        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $query = $this->db->prepare('DELETE FROM products WHERE product_id = ?');
        $query->execute([$id]);
    }

    public function update($name, $description, $price, $stock, $category_id, $id) {
        $query = $this->db->prepare('UPDATE products 
                                    SET name = ?, description = ?, price = ?, stock = ?, category_id = ? 
                                    WHERE product_id = ?');
        $query->execute([$name, $description, $price, $stock, $category_id, $id]);
    }
}