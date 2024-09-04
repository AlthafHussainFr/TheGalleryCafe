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

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in to view your orders.'); window.location.href='login.php';</script>";
    exit();
}

$user = $_SESSION['username'];

// Fetch all orders for the logged-in user
$sql = "SELECT o.id, o.food, o.quantity, o.status, o.parking_slot, m.meal_price, o.username, o.table_number, o.delivery_estimated_time
        FROM orders o
        JOIN srilankan_meal m ON o.food = m.meal_name
        WHERE o.username = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error fetching orders: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .total-price {
            font-size: 1.5em;
            margin-top: 20px;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 1em;
            cursor: pointer;
        }
        @media print {
            .note {
                display: block;
                text-align: center;
                margin-top: 20px;
                font-weight: bold;
            }
            .print-button, .home-button {
                display: none;
            }
        }
        .note {
            display: none;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
    <script>
        function printOrders() {
            window.print();
        }

        function goHome() {
            window.location.href = 'customerpage.html';
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Your Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Food</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Table Number</th>
                    <th>Delivery Estimated Time</th>
                    <th>Parking Slot</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php
                    $totalPrice = 0;
                    while ($row = $result->fetch_assoc()): 
                        $totalPrice += $row['meal_price'] * $row['quantity'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['food']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>Rs. <?php echo number_format($row['meal_price'], 2); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                            <td><?php echo htmlspecialchars($row['table_number'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_estimated_time'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['parking_slot'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="8" class="total-price">Total Price:</td>
                        <td class="total-price">Rs. <?php echo number_format($totalPrice, 2); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="9">You have no orders.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button class="btn home-button" onclick="goHome()">Home</button>
        <button class="btn print-button" onclick="printOrders()">Print</button>
        <div class="note">
            Give your digital receipt to the cashier. <br>
            Thank you, enjoy your meal!
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
