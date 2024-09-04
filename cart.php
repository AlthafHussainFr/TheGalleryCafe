<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thegallerycafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update cart quantity
        foreach ($_POST['quantity'] as $meal_id => $quantity) {
            $_SESSION['cart'][$meal_id] = intval($quantity);
        }
    } elseif (isset($_POST['remove'])) {
        // Remove item from cart
        $meal_id = intval($_POST['remove']);
        unset($_SESSION['cart'][$meal_id]);
    } elseif (isset($_POST['checkout'])) {
        // Check if user is logged in
        if (isset($_SESSION['username'])) {
            header("Location: checkout.php");
            exit();
        } else {
            header("Location: signup.php");
            exit();
        }
    }
}

// Fetch cart items
$cart_items = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT id, meal_name, meal_price, meal_picture FROM srilankan_meal WHERE id IN ($cart_ids)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-details h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }

        .cart-item-details p {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }

        .cart-item-details input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-right: 10px;
        }

        .update, .checkout-button, .view-menu-button {
            background-color: #fe5722;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .update:hover, .checkout-button:hover, .view-menu-button:hover {
            background-color: #e64a19;
        }

        .total-price {
            text-align: right;
            font-size: 20px;
            color: #333;
            margin-top: 20px;
        }

        .container a {
            color: #fe5722;
            text-decoration: none;
            transition: color 0.3s;
        }

        .container a:hover {
            color: #e64a19;
        }

        .cart-item button {
            background-color: #fe5722;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .cart-item button:hover {
            background-color: #e64a19;
        }

        .checkout-button, .view-menu-button {
            display: block;
            width: calc(100% - 22px); /* Full width minus padding */
            text-align: center;
            margin: 20px auto;
        }

        .small-button {
            background-color: #000;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin: 5px 10px;
        }

        .small-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Cart</h1>
        <form action="cart.php" method="post">
            <?php if (empty($cart_items)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['meal_picture']; ?>" alt="<?php echo $item['meal_name']; ?>">
                        <div class="cart-item-details">
                            <h3><?php echo $item['meal_name']; ?></h3>
                            <p>Price: Rs. <?php echo $item['meal_price']; ?></p>
                            <div class="cart-item-actions">
                                <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo isset($_SESSION['cart'][$item['id']]) ? $_SESSION['cart'][$item['id']] : 1; ?>" min="1">
                                <button type="submit" name="update" value="Update" class="update">Update Quantity</button>
                                <button type="submit" name="remove" value="<?php echo $item['id']; ?>">Remove</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="total-price">
                    <?php
                    $total = 0;
                    foreach ($cart_items as $item) {
                        $total += $item['meal_price'] * $_SESSION['cart'][$item['id']];
                    }
                    echo "Total: Rs. " . number_format($total, 2);
                    ?>
                </div>
                <button type="submit" name="checkout" class="checkout-button">Proceed to Checkout</button>
            <?php endif; ?>
        </form>
        <a href="sl.php" class="small-button">View Menu</a>
        <a href="cview.php" class="small-button">View Already Booked Food</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
