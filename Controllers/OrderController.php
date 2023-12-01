<?php

class OrderController {
    private $model;
    private $customerModel;
    private $cartModel;

    public function __construct($orderModel,$customerModel,$cartModel) {
        $this->model = $orderModel;
        $this->customerModel = $customerModel;
        $this->cartModel = $cartModel;
    }


    public function createOrderAndShowList($customerID, $shippingAddress) {
        $orderID = $this->model->createOrder($customerID, $shippingAddress);
        $this->customerModel = $customerModel;

        if ($orderID) {
            header("Location: index.php?action=show_order_list");
            exit;
        } else {
            // Handle error
            header("Location: index.php?action=order_error");
            exit;
        }
    }

    public function createOrder($customerID, $shippingAddress) {

        $orderID = $this->model->createOrder($customerID, $shippingAddress);

        if ($orderID) {
            // Redirect to an order confirmation page or show a success message
            header("Location: index.php?action=order_confirmation&order_id=$orderID");
            exit;
        } else {
            // Handle error, maybe redirect to an error page or show an error message
            header("Location: index.php?action=order_error");
            exit;
        }
    }

    public function placeOrder($customerID, $shippingAddress, $paymentStatus, $orderStatus) {
        // Calculate total amount from cart
        $totalAmount = $this->cartModel->calculateCartTotal($customerID);

        // Create Order
        $orderID = $this->model->createOrder($customerID, $shippingAddress, $totalAmount, $paymentStatus, $orderStatus);

        // Add items to OrderDetails
        $this->model->addItemsToOrderDetails($orderID, $customerID);

        // Clear the cart
        $this->cartModel->clearCart($customerID);

        header("Location: ../views/Orders/orderConfirmation.php?order_id=" . $orderID);
    }


    public function showOrderConfirmationForm($customerID) {
        // Fetch customer details
        $customer = $this->customerModel->getCustomerDetails($customerID);
        $totalAmount = $this->cartModel->calculateCartTotal($customerID);
        include '../views/Orders/orderConfirmationForm.php';
       
    }

    // Additional methods related to orders can be added here
}
