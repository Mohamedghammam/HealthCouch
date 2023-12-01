<!-- views/orderList.php -->

<h1>Your Orders</h1>

<?php if (!empty($orders)): ?>
    <ul>
        <?php foreach ($orders as $order): ?>
            <li>
                Order ID: <?= htmlspecialchars($order['OrderID']) ?> - 
                Total: <?= htmlspecialchars($order['TotalAmount']) ?>
                <!-- Add more details as needed -->
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>
