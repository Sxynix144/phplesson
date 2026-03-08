<?php   
require 'config.php';

$stmt = $restaurant->query("SELECT * FROM customer");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtOrders = $restaurant->query("
    SELECT o.order_id, o.customer_id, o.item_id, o.order_date, o.quantity, c.first_name, c.last_name, c.phone_number, 
    m.dish_name, m.price, m.category 
    FROM orders o 
    JOIN customer c ON o.customer_id = c.customer_id 
    JOIN menuitems m ON o.item_id = m.item_id
    ORDER BY o.order_id DESC
");



?>