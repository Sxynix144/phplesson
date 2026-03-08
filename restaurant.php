<?php
require 'config.php';


if(isset($_POST['update_customer'])){
    $customer_id = $_POST['customer_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    
    $stmt = $restaurant->prepare("UPDATE customer SET first_name = ?, last_name = ?, phone_number = ? WHERE customer_id = ? ");
    $stmt->execute([$first_name, $last_name, $phone_number, $customer_id]);
    header("Location: restaurant.php?tab=customers");
    exit;
}


$edit_customer = null;
if(isset($_GET['update_customer'])){
    $stmt = $restaurant->prepare("SELECT * FROM customer WHERE customer_id = ?");
    $stmt->execute([$_GET['update_customer']]);
    $edit_customer = $stmt->fetch(PDO::FETCH_ASSOC);
}
$edit_menuitems = null;
if(isset($_GET['update_item'])){
    $stmt = $restaurant->prepare("SELECT * FROM menuitems WHERE item_id = ?");
    $stmt->execute([$_GET['update_item']]);
    $edit_menuitems = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['delete_customer'])) {
    $stmt = $restaurant->prepare("DELETE FROM customer WHERE customer_id = ?");
    $stmt->execute([$_GET['delete_customer']]);
    header("Location: restaurant.php?tab=customers"); 
}

if (isset($_GET['delete_item'])) {
    $stmt = $restaurant->prepare("DELETE FROM menuitems WHERE item_id = ?");
    $stmt->execute([$_GET['delete_item']]);
    header("Location: restaurant.php?tab=menu");
}

if (isset($_GET['delete_order'])) {
    $stmt = $restaurant->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->execute([$_GET['delete_order']]);
    header("Location: restaurant.php?tab=orders");
}


if (isset($_POST['add_customer'])) {
    $stmt = $restaurant->prepare("INSERT INTO customer (first_name, last_name, phone_number) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['first_name'], $_POST['last_name'], $_POST['phone']]);
}

if (isset($_POST['add_menu_item'])) {
    $stmt = $restaurant->prepare("INSERT INTO menuitems (dish_name, price, category) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['dish_name'], $_POST['price'], $_POST['category']]);
}
if(isset($_POST['update_item'])){
    $item_id = $_POST['item_id'];
    $dish_name = $_POST['dish_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    $stmt = $restaurant->prepare("UPDATE menuitems SET dish_name = ?, price = ?, category = ? WHERE item_id = ?");
    $stmt->execute([$dish_name, $price, $category, $item_id]);
    header("Location: restaurant.php?tab=menu");
    exit;
}
if(isset($_POST['update_item'])){
    $item_id = $_POST['item_id'];
    $dish_name = $_POST['dish_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    $stmt = $restaurant->prepare("UPDATE menuitems SET dish_name = ?, price = ?, category = ? WHERE item_id = ?");
    $stmt->execute([$dish_name, $price, $category, $item_id]);
    header("Location: restaurant.php?tab=menu");
    exit;
}

if (isset($_POST['place_order'])) {
    $order_date = date('Y-m-d H:i:s');
    $stmt = $restaurant->prepare("INSERT INTO orders (customer_id, item_id, order_date, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['customer_id'], $_POST['item_id'], $order_date, $_POST['quantity']]);
}


$customers = $restaurant->query("SELECT * FROM customer ORDER BY customer_id DESC")->fetchAll(PDO::FETCH_ASSOC);
$menu_items = $restaurant->query("SELECT * FROM menuitems ORDER BY item_id DESC")->fetchAll(PDO::FETCH_ASSOC);
$orders_query = $restaurant->query("
    SELECT o.order_id, c.first_name, c.last_name, m.dish_name, m.price, o.quantity, o.order_date 
    FROM orders o 
    JOIN customer c ON o.customer_id = c.customer_id 
    JOIN menuitems m ON o.item_id = m.item_id 
    ORDER BY o.order_id DESC
");
$orders = $orders_query->fetchAll(PDO::FETCH_ASSOC);


$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'orders';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALLLEN NA Restaurant na nag Management</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .body 
        { 
            background-color: #de3afb; 
        }
        .nav-tabs .nav-link 
        { color: #495057; 
        font-weight: 500; 
        border: none;
     }
        .nav-tabs .nav-link.active { 
            border-bottom: 2px solid #0d6efd; 
            color: #0d6efd; 
            background: transparent; }
        .card { 
            border-radius: 10px; 
            border: none; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }
        .btn-delete {
             color: #dc3545; 
             text-decoration: none;
              cursor: pointer; }
        .btn-delete:hover { 
            text-decoration: underline; 
        }
    </style>
</head>
<body>

<div class="container py-5">
  
    <ul class="nav nav-tabs mb-4 justify-content-start border-bottom-0" id="mainTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= $active_tab == 'orders' ? 'active' : '' ?>" href="?tab=orders">Orders</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $active_tab == 'customers' ? 'active' : '' ?>" href="?tab=customers">Customers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $active_tab == 'menu' ? 'active' : '' ?>" href="?tab=menu">Menu Items</a>
        </li>
    </ul>

    <div class="tab-content pt-3">
        
        <?php 
        if($active_tab == 'customers'):
             ?>
        <div class="card p-4">
            <?php if($edit_customer): ?>
                <h5 class="mb-4">Edit Customer</h5>
                <form action="restaurant.php?tab=customers" method="POST" class="row g-3 mb-5">
                    <input type="hidden" name="customer_id" value="<?= $edit_customer['customer_id'] ?>">
                    <div class="col-md-3">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?= htmlspecialchars($edit_customer['first_name']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?= htmlspecialchars($edit_customer['last_name']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value="<?= htmlspecialchars($edit_customer['phone_number']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="update_customer" class="btn btn-success w-100">Save Changes</button>
                    </div>
                </form>
                <a href="restaurant.php?tab=customers" class="btn btn-secondary btn-sm">Cancel</a>
                <hr>
            <?php endif; ?>
            <h5 class="mb-4">Add New Customer</h5>
            <form action="restaurant.php?tab=customers" method="POST" class="row g-3 mb-5">
                <div class="col-md-3">
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="add_customer" class="btn btn-primary w-100">Add Customer</button>
                </div>
            </form>

            <table class="table align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars(strtoupper($c['first_name'] . ' ' . $c['last_name'])) ?></td>
                        <td><?= htmlspecialchars($c['phone_number']) ?></td>
                        <td><a href="?tab=customers&update_customer=<?= $c['customer_id'] ?>" class="btn-edit" onclick="return confirm('Update this customer?')">Edit</a>
                            <a href="?tab=customers&delete_customer=<?= $c['customer_id'] ?>" class="btn-delete" onclick="return confirm('Delete this customer?')">Delete 
                        </a></td>

                    </tr>
                      
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if($active_tab == 'menu'): ?>
        <div class="card p-4">
            <h5 class="mb-4">Add Menu Item</h5>
            <form action="restaurant.php?tab=menu" method="POST" class="row g-3 mb-5">
                <div class="col-md-3">
                    <input type="text" name="dish_name" class="form-control" placeholder="Dish Name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="category" class="form-control" placeholder="Category (e.g. Pasta)" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="add_menu_item" class="btn btn-primary w-100">Save Item</button>
                </div>
            </form>

            <?php if($edit_menuitems): ?>
                <h5 class="mb-4">Edit Menu Item</h5>
                <form action="restaurant.php?tab=menu" method="POST" class="row g-3 mb-5">
                    <input type="hidden" name="item_id" value="<?= $edit_menuitems['item_id'] ?>">
                    <div class="col-md-3">
                        <input type="text" name="dish_name" class="form-control" placeholder="Dish Name" value="<?= htmlspecialchars($edit_menuitems['dish_name']) ?>" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" value="<?= htmlspecialchars($edit_menuitems['price']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="category" class="form-control" placeholder="Category (e.g. Pasta)" value="<?= htmlspecialchars($edit_menuitems['category']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="update_item" class="btn btn-success w-100">Save Changes</button>
                    </div>
                </form>
                <a href="restaurant.php?tab=menu" class="btn btn-secondary btn-sm">Cancel</a>
                <hr>
            <?php endif; ?>

            <table class="table align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th>Dish</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($menu_items as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['dish_name']) ?></td>
                        <td><?= htmlspecialchars($m['category']) ?></td>
                        <td>₱<?= number_format($m['price'], 2) ?></td>
                        <td><a href="?tab=menu&update_item=<?= $m['item_id'] ?>" class="btn-edit" onclick="return confirm('Update this item?')">Edit</a>
                            <a href="?tab=menu&delete_item=<?= $m['item_id'] ?>" class="btn-delete" onclick="return confirm('Delete item?')">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if($active_tab == 'orders'): ?>
        <div class="card p-4 mb-4">
            <h5 class="mb-4">Place New Order</h5>
            <form action="restaurant.php?tab=orders" method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-muted small">Customer</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        <?php foreach($customers as $c): ?>
                            <option value="<?= $c['customer_id'] ?>"><?= $c['first_name'] ?> <?= $c['last_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Menu Item</label>
                    <select name="item_id" class="form-select" required>
                        <option value="">Select Dish</option>
                        <?php foreach($menu_items as $m): ?>
                            <option value="<?= $m['item_id'] ?>"><?= $m['dish_name'] ?> (₱<?= $m['price'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-muted small">Qty</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="place_order" class="btn btn-primary w-100">Submit Order</button>
                </div>
            </form>
        </div>

        <div class="card p-4">
            <table class="table align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Dish</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $o): ?>
                    <tr>
                        <td>#<?= $o['order_id'] ?></td>
                        <td><?= htmlspecialchars($o['first_name'] . ' ' . $o['last_name']) ?></td>
                        <td><?= htmlspecialchars($o['dish_name']) ?> (x<?= $o['quantity'] ?>)</td>
                        <td>₱<?= number_format($o['price'] * $o['quantity'], 2) ?></td>
                        <td><?= date('M d, H:i', strtotime($o['order_date'])) ?></td>
                        <td><a href="?tab=orders&delete_order=<?= $o['order_id'] ?>" class="btn-delete text-muted fs-5">&times;</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>