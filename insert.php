<?php  
    require 'config.php';

    if(isset($_POST['add'])) {

        
        $first_name= isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
        $dish_name = isset($_POST['dish_name']) ? $_POST['dish_name'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $order_date= isset($_POST['order_date']) ? $_POST['order_date'] : ''; 
        $quantity= isset($_POST['quantity']) ? $_POST['quantity'] : '';

      
        if(empty($first_name) || empty($last_name) || empty($phone_number) || empty($dish_name)
            || empty($price) || empty($category) || empty($order_date) || empty($quantity)) {
            echo "All fields are required!";
            exit;
        }

      
        $stmt = $restaurant->prepare("INSERT INTO customer (first_name, last_name, phone_number) VALUES (?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $phone_number]);

        $customer_id = $restaurant->lastInsertId();  

        $stmt2 = $restaurant->prepare("INSERT INTO menuitems (dish_name, price, category) VALUES (?, ?, ?)");
        $stmt2->execute([ $dish_name, $price, $category]);  
         $item_id = $restaurant->lastInsertId();
        echo "User and Order Added successfully";
       

        $stmt3 = $restaurant->prepare("INSERT INTO orders (customer_id, item_id, order_date, quantity) VALUES (?, ?, ?, ?)");
        $stmt3->execute([$customer_id, $item_id, $order_date, $quantity]);  
        $order_id = $restaurant->lastInsertId();
        echo "User and Order Added successfully";
      
    }
?>  