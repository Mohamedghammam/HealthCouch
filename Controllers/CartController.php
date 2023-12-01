<?php

class CartController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showCart($customerID) {
        $cartItems = $this->model->getCartItems($customerID);
        include '../views/Cart/showCart.php';
    }

    public function deleteCartItem($customerID, $productID) {
        $result = $this->model->deleteCartItem($customerID, $productID);
        // Redirect or show a message based on $result
        // Redirect to the cart view or show a message
        header('Location: index.php?action=show_cart');
        exit;
    }

    public function updateCartItemQuantity($customerID, $productID, $newQuantity) {
        $result = $this->model->updateCartItemQuantity($customerID, $productID, $newQuantity);
        // Redirect or show a message based on $result
        // Redirect to the cart view or show a message
        header('Location: index.php?action=show_cart');
        exit;
    }

    public function addProductToCart($customerID, $productID) {
        $this->model->addProductToCart($customerID, $productID);
        header("Location: index.php?action=show_products"); 
        exit;
    }
}
