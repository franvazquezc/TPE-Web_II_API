<?php
require_once 'app/controllers/api.controller.php';
require_once 'app/models/product.model.php';

class ProductApiController extends ApiController {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new ProductModel();
    }

    public function get($params = []) {
        //----------GET LISTA DE PRODUCTOS----------
        if(empty($params)) {
            // CONTROL DE ERRORES EN LOS PARAMETROS
            // Se chequea que solo haya parametros permitidos
            $allowedParams = ['resource', 'search_by', 'sought_value', 'sort', 'order', 'page', 'page_size'];
            $illegalParam = false;
            foreach(array_keys($_GET) as $key) {
                if (!in_array($key, $allowedParams)) {
                    $illegalParam = true;
                    break;
                }
            }
            if($illegalParam) {
                $this->view->response('No se puede interpretar la solicitud', 400);
                return;
            }
            // Control de parametros de filtros
            if(isset($_GET['search_by']) && isset($_GET['sought_value'])) {
                $productAttributes = $this->model->getAttributes();
                // Si el parametro search_by no es un campo de la tabla
                if(!in_array($_GET['search_by'], $productAttributes)) {
                    $this->view->response('No se puede interpretar la solicitud', 400);
                    return;
                }
            }
            // Control de parametros de orden
            if(isset($_GET['sort']) && isset($_GET['order'])) {
                $productAttributes = $this->model->getAttributes();
                // Si el parametro order no es DESC ni ASC, o el parametro sort no es un campo de la tabla
                if((!($_GET['order'] == "DESC") && !($_GET['order'] == "ASC")) ||
                    !(in_array($_GET['sort'], $productAttributes))) {
                    $this->view->response('No se puede interpretar la solicitud', 400);
                    return;    
                }
            }
            // Control de paginado
            if(isset($_GET['page']) && isset($_GET['page_size'])) {
                // Si el nro de pagina y el tamaño de la misma no son enteros positivos
                if(!is_numeric($_GET['page']) || !is_int($_GET['page'] + 0) || !(int)$_GET['page'] > 0
                    || !is_numeric($_GET['page_size']) || !is_int($_GET['page_size'] + 0) || !(int)$_GET['page_size'] > 0){
                    $this->view->response('No se puede interpretar la solicitud', 400);
                    return;
                }
            }
            // CONSULTA AL MODELO
            $products = $this->model->get($_GET);
            // RTA
            if($products) {
                $this->view->response($products, 200);
            } else {
                $this->view->response('Not found', 404);
            } 
        //----------GET PRODUCTO POR ID----------
        } else {
            $product = $this->model->get($params);
            if(!empty($product)) {
                $this->view->response($product, 200);
            } else {
                $this->view->response('El producto con el id=' . $params[':ID'] . ' no fue encontrado.', 404);
            }
        }
    }

    public function delete($params = []) {
        $product_id = $params[':ID'];
        $product = $this->model->get($params);

        if ($product) {
            $this->model->delete($product_id);
            $this->view->response("El producto con id=$product_id fue eliminado con éxito", 200);
        } else {
            $this->view->response("El producto con el id=$product_id no fue encontrado", 404);
        }
    }

    public function add() {    
        $body = $this->getData();

        $name = $body->name;
        $description = $body->description;
        $price = $body->price;
        $stock = $body->stock;
        $category_id = $body->category_id;

        $this->model->insert($name, $description, $price, $stock, $category_id);
        $this->view->response("El producto fue agregado con éxito", 200);
    }

    public function update($params = []) {
        $product_id = $params[':ID'];
        $product = $this->model->get($params);

        if ($product) {
            $body = $this->getData();
            $name = $body->name;
            $description = $body->description;
            $price = $body->price;
            $stock = $body->stock;
            $category_id = $body->category_id;

            $this->model->update($name, $description, $price, $stock, $category_id, $product_id);
            $this->view->response("El producto con id=$product_id fue modificado con éxito", 200);
        } else {
            $this->view->response("El producto con id=$product_id no fue encontrado", 404);
        }
    }

    public function default() {
        $this->view->response("Recurso no encontrado", 404);
    }
}