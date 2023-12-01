<?php

class OrderModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function createOrder($customerID, $shippingAddress, $totalAmount, $paymentStatus, $orderStatus) {
        $query = "INSERT INTO MyOrder (CustomerID, OrderDate, ShippingAddress, TotalAmount, PaymentStatus, OrderStatus) VALUES (?, NOW(), ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issss", $customerID, $shippingAddress, $totalAmount, $paymentStatus, $orderStatus);
        $stmt->execute();

        return $this->db->insert_id;
    }

    public function addItemsToOrderDetails($orderID, $customerID) {
        $cartItemsQuery = "SELECT ProductID, Quantity FROM Cart WHERE CustomerID = ?";
        $cartStmt = $this->db->prepare($cartItemsQuery);
        $cartStmt->bind_param("i", $customerID);
        $cartStmt->execute();
        $cartItemsResult = $cartStmt->get_result();
    
        while ($cartItem = $cartItemsResult->fetch_assoc()) {
            $insertDetailQuery = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, (SELECT Price FROM Product WHERE ProductID = ?))";
            $detailStmt = $this->db->prepare($insertDetailQuery);
            $detailStmt->bind_param("iiii", $orderID, $cartItem['ProductID'], $cartItem['Quantity'], $cartItem['ProductID']);
            $detailStmt->execute();
        }
    }

    public function getOrderDetails($orderID) {
        $query = "SELECT * FROM MyOrder WHERE OrderID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row; // Returns the order details
        } else {
            return null;
        }
    }
    
}
