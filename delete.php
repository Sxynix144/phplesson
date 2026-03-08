<?php
require 'config.php';

if(isset($_GET['delete'])){
    $customer_id = $_GET['delete'];

    $stmt = $restaurant->prepare("DELETE FROM customer  WHERE customer_id = ?");
    $stmt->execute([$customer_id]);

}
?>

