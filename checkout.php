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

// Redirect to signup if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
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

// Handle order confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $username = $_SESSION['username'];
    $total = 0;

    // Prepare the statement
    $order_query = "INSERT INTO orders (username, food, quantity, total) VALUES (?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_query);

    // Check if statement preparation was successful
    if ($order_stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    foreach ($cart_items as $item) {
        $meal_name = $item['meal_name'];
        $quantity = $_SESSION['cart'][$item['id']];
        $item_total = $item['meal_price'] * $quantity;
        $total += $item_total;

        // Bind parameters and execute
        if (!$order_stmt->bind_param("ssii", $username, $meal_name, $quantity, $item_total)) {
            die("Bind failed: " . $order_stmt->error);
        }
        if (!$order_stmt->execute()) {
            die("Execute failed: " . $order_stmt->error);
        }
    }

    // Close the statement
    $order_stmt->close();

    // Clear the cart
    unset($_SESSION['cart']);

    echo '<script>
        alert("Order confirmed successfully.");
        window.location.href = "thankyou.php";
    </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
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

        .total-price {
            text-align: right;
            font-size: 20px;
            color: #333;
            margin-top: 20px;
        }

        .button {
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

        .button:hover {
            background-color: #e64a19;
        }

        .back-button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .back-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <form action="checkout.php" method="post">
            <?php if (empty($cart_items)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['meal_picture']; ?>" alt="<?php echo $item['meal_name']; ?>">
                        <div class="cart-item-details">
                            <h3><?php echo $item['meal_name']; ?></h3>
                            <p>Price: Rs. <?php echo $item['meal_price']; ?></p>
                            <p>Quantity: <?php echo $_SESSION['cart'][$item['id']]; ?></p>
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
                <button type="submit" name="confirm" class="button">Confirm Order</button>
            <?php endif; ?>
        </form>
        <a href="cart.php" class="back-button">Back to Cart</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
