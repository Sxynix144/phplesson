<?php

require 'config.php';

if(isset($_POST['update'])){
$customer_id = $_POST['customer_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone_number = $_POST['phone_number'];

$stmt = $restaurant->prepare("UPDATE customer SET first_name = ?, last_name = ?, phone_number = ? WHERE customer_id = ? ");
$stmt->execute([$first_name,$last_name, $phone_number,$customer_id]);

}



?>