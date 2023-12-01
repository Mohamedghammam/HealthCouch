
<?php

class CartModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getCartItems($customerID) {
        $sql = "SELECT Cart.*, Product.ProductName, Product.Price 
                FROM Cart 
                JOIN Product ON Cart.ProductID = Product.ProductID 
                WHERE CustomerID = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function deleteCartItem($customerID, $productID) {
        $sql = "DELETE FROM Cart WHERE CustomerID = ? AND ProductID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $customerID, $productID);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function updateCartItemQuantity($customerID, $productID, $newQuantity) {
        $sql = "UPDATE Cart SET Quantity = ? WHERE CustomerID = ? AND ProductID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $newQuantity, $customerID, $productID);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function addProductToCart($customerID, $productID) {
        // Check if the product is already in the cart
        $stmt = $this->db->prepare("SELECT * FROM Cart WHERE CustomerID = $customerID AND ProductID = $productID");
        //$stmt->bind_param("ii", $customerID, $productID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the quantity if the product is already in the cart
            $updateStmt = $this->db->prepare("UPDATE Cart SET Quantity = Quantity + 1 WHERE CustomerID = ? AND ProductID = ?");
        } else {
            // Insert the new product into the cart
            $updateStmt = $this->db->prepare("INSERT INTO Cart (CustomerID, ProductID, Quantity) VALUES (?, ?, 1)");
        }
        $updateStmt->bind_param("ii", $customerID, $productID);
        $updateStmt->execute();
    }

    public function calculateCartTotal($customerID) {
        $query = "SELECT SUM(p.Price * c.Quantity) AS TotalAmount 
                  FROM Cart c 
                  JOIN Product p ON c.ProductID = p.ProductID 
                  WHERE c.CustomerID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['TotalAmount'];
        } else {
            return 0; // Or handle as appropriate
        }
    }


    public function clearCart($customerID) {
        $query = "DELETE FROM Cart WHERE CustomerID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
    }
    

    

   
}
