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

// Handle updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $status = isset($_POST['action']) && $_POST['action'] === 'accept' ? 'accepted' : 'rejected';
    $tableNumber = $_POST['table_number'] ? intval($_POST['table_number']) : NULL;
    $deliveryTime = $_POST['delivery_time'] ? $_POST['delivery_time'] : NULL;

    // Update query
    $updateQuery = "UPDATE orders SET status = ?, table_number = ?, delivery_estimated_time = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param('sisi', $status, $tableNumber, $deliveryTime, $orderId);

    if ($stmt->execute()) {
        echo "<script>alert('Order updated successfully.'); window.location.href='view.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch orders
$sql = "SELECT id, username, food, quantity, table_number, delivery_estimated_time, status FROM orders";
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching orders: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders Management</title>
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
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            font-size: 0.9em;
        }
        .accept {
            background-color: #4CAF50;
        }
        .reject {
            background-color: #f44336;
        }
        .btn.disabled {
            background-color: #b0bec5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Orders Management</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Food</th>
                    <th>Quantity</th>
                    <th>Table Number</th>
                    <th>Delivery Estimated Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['food']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['table_number'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_estimated_time'] ?? 'N/A'); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                            <td>
                                <form action="view.php" method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="number" name="table_number" value="<?php echo htmlspecialchars($row['table_number'] ?? ''); ?>" placeholder="Table Number" required>
                                    <input type="time" name="delivery_time" value="<?php echo htmlspecialchars($row['delivery_estimated_time'] ?? ''); ?>" placeholder="Delivery Time" required>
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <input type="submit" name="action" value="accept" class="btn accept" />
                                        <input type="submit" name="action" value="reject" class="btn reject" />
                                    <?php else: ?>
                                        <button type="submit" class="btn accept" disabled>Update</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
