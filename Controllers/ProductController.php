<?php


class ProductController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function listProducts() {
        $products = $this->model->getAllProducts();
        include __DIR__ . '/../Views/products/productList.php';

    }
}
