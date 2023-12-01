<?php
// public/index.php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../models/ProductModel.php';
require_once '../Controllers/ProductController.php';
require_once '../models/CartModel.php';
require_once '../Controllers/CartController.php';
require_once '../models/OrderModel.php';
require_once '../Controllers/OrderController.php';
require_once '../models/CustomerModel.php';

// Database connection setup
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "e-pharmacy";

// Create connection
$dbConnection = new mysqli($servername, $username, $password, $dbname);
if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
}

// Initialize models and controllers
$productModel = new ProductModel($dbConnection);
$productController = new ProductController($productModel);
$cartModel = new CartModel($dbConnection);
$cartController = new CartController($cartModel);
$orderModel = new OrderModel($dbConnection);
$customerModel = new CustomerModel($dbConnection);
$orderController = new OrderController($orderModel, $customerModel,$cartModel);

// Get customer ID (replace with actual logic to get logged-in user ID)
$customerID = 1;

// Routing based on the 'action' GET parameter
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'show_products':
            $productController->listProducts();
            break;
        case 'show_cart':
            $cartController->showCart($customerID);
            break;
        case 'update_quantity':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $productID = $_POST['productID'];
                $newQuantity = $_POST['newQuantity'];
                $cartController->updateCartItemQuantity($customerID, $productID, $newQuantity);
            }
            break;
        case 'delete_item':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $productID = $_POST['productID'];
                $cartController->deleteCartItem($customerID, $productID);
            }
            break;


        // Add other cases as needed

        case 'add_to_cart':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $customerID = 1; // Replace with the actual customer ID from session
                $productID = $_POST['productID'];
                $cartController->addProductToCart($customerID, $productID);
            }
            break;
        

        case 'place_order':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Assuming $customerID is available from the session or similar context
                $shippingAddress = $_POST['Address']; // Validate and sanitize
                $paymentStatus = 'Pending'; // Example status, adjust as needed
                $orderStatus = 'Processing'; // Example status, adjust as needed

                $orderController->placeOrder($customerID, $shippingAddress, $paymentStatus, $orderStatus);

                // Redirect to an order confirmation page
                // You might want to pass the returned $orderID for displaying specific order details
            }
            break;

        case 'order_confirmation_form':
                $customerID = 1; // Replace with actual customer ID from session or context
                $orderController->showOrderConfirmationForm($customerID);
                break;
    
        case 'order_confirmation':
            $orderID = $_GET['order_id'] ?? null;
            include 'views/orderConfirmation.php';
            break;

        case 'show_order_list':
            $orderController->showOrderList($customerID);
            break;
    
        case 'order_error':
                // Include an order error view or handle the error
            break;

            case 'main':
                header('Location: /HEALTHCOUCH/about.html');
                exit;
                // Include an order error view or handle the error
        break;

        default:
            // Default action (could be a 404 page or redirect to a default page)
            break;
        

    }
} else {
    // Default to showing the product list if no specific action is requested
    header('Location: /HEALTHCOUCH/about.html');
    exit;
}

// Close database connection
$dbConnection->close();
?>
