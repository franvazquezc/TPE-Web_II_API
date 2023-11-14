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
        if(empty($params)) {
            $products = $this->model->get($_GET);
            if($products) {
                $this->view->response($products, 200);
            } else {
                $this->view->response('No se puede interpretar la solicitud', 400);
            } 
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

    public function error() {
        $this->view->response("Recurso no encontrado", 404);
    }
}