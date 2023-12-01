<?php

class CustomerModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getCustomerDetails($customerID) {
        // Prepare a SQL query to select customer details
        $query = "SELECT * FROM Customer WHERE CustomerID = $customerID";

        // Prepare the SQL statement
        $stmt = $this->db->prepare($query);

    

        // Execute the query
        $stmt->execute();

        // Get the results of the query
        $result = $stmt->get_result();
        
        // Check if any record was found
        if ($result->num_rows > 0) {
            // Fetch the customer details
            $customerDetails = $result->fetch_assoc();
            return $customerDetails;
        } else {
            // No record found, return null or handle as appropriate
            echo("nothing");
            return null;
        }
    }

    // ... other methods ...
}
