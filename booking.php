<?php
session_start(); // Start the session at the beginning of the script

$host = "localhost";
$username = "root";
$password = "";
$database = "thegallerycafe";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitizeInput($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

// Redirect to signup.php if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

// Handle slot booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["slot"])) {
    $slot = sanitizeInput($_POST["slot"]);
    $username = $_SESSION['username']; // Get logged-in username

    // Check if the user has any existing order without a parking slot
    $checkOrderQuery = "SELECT id FROM orders WHERE username = ? AND parking_slot IS NULL LIMIT 1";
    $checkOrderStatement = $conn->prepare($checkOrderQuery);
    $checkOrderStatement->bind_param("s", $username);
    $checkOrderStatement->execute();
    $checkOrderResult = $checkOrderStatement->get_result();

    if ($checkOrderResult->num_rows > 0) {
        // Update existing order with the parking slot
        $order = $checkOrderResult->fetch_assoc();
        $orderId = $order['id'];
        
        $updateQuery = "UPDATE orders SET parking_slot = ?, status = 'pending' WHERE id = ?";
        $updateStatement = $conn->prepare($updateQuery);
        $updateStatement->bind_param("ii", $slot, $orderId);

        if ($updateStatement->execute()) {
            echo '<script>alert("Slot booked successfully.");</script>';
        } else {
            echo "Error: " . $updateStatement->error;
        }
    } else {
        // Check if the slot is already booked by another user
        $checkSlotQuery = "SELECT * FROM orders WHERE parking_slot = ?";
        $checkSlotStatement = $conn->prepare($checkSlotQuery);
        $checkSlotStatement->bind_param("i", $slot);
        $checkSlotStatement->execute();
        $checkSlotResult = $checkSlotStatement->get_result();

        if ($checkSlotResult->num_rows > 0) {
            echo '<script>alert("Slot is already booked by another user. Please choose another slot.");</script>';
        } else {
            // Insert new order with the parking slot
            $insertQuery = "INSERT INTO orders (username, parking_slot, status) VALUES (?, ?, 'pending')";
            $insertStatement = $conn->prepare($insertQuery);
            $insertStatement->bind_param("si", $username, $slot);

            if ($insertStatement->execute()) {
                echo '<script>alert("Slot booked successfully.");</script>';
            } else {
                echo "Error: " . $insertStatement->error;
            }
        }
    }
}

// Fetch booked slots with user details
$slotsQuery = "SELECT username, parking_slot FROM orders WHERE parking_slot IS NOT NULL";
$slotsResult = $conn->query($slotsQuery);
$bookedSlots = [];
if ($slotsResult) {
    while ($row = $slotsResult->fetch_assoc()) {
        $bookedSlots[] = $row;
    }
} else {
    echo "Error fetching booked slots: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Parking Slot</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333; /* Dark background for the entire page */
            color: #fff; /* White text color */
            text-align: center;
        }

        h1 {
            margin-top: 20px;
            color: #f8f8f8; /* Light color for the heading */
        }

        #bookingForm {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px auto;
            max-width: 500px;
            background-color: #1e1e1e; /* Darker background for the form container */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Subtle shadow for depth */
        }

        .parking-slot {
            width: 50px;
            height: 50px;
            background-color: #3b3b3b; /* Dark grey background for slots */
            border: 2px solid #444; /* Slightly lighter border */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .parking-slot.booked {
            background-color: #6f4e37; /* Brownish color for booked slots */
            color: #fff;
            border-color: #5a3c1e; /* Darker brown for border */
        }

        .parking-slot.available {
            background-color: #8e735b; /* Light brown for available slots */
            color: #fff;
            border-color: #6f4e37; /* Brown border */
        }

        .parking-slot:hover {
            background-color: #7d5a3e; /* Slightly darker brown on hover */
            border-color: #5a3c1e; /* Darker border on hover */
        }

        .home-button {
            display: inline-block;
            margin: 20px auto;
            padding: 12px 24px;
            background-color: #6f4e37; /* Brownish button background */
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }

        .home-button:hover {
            background-color: #5a3c1e; /* Darker brown on hover */
            transform: scale(1.05);
        }

    </style>
</head>
<body>
    <h1>Book a Parking Slot</h1>
    <form id="bookingForm" action="booking.php" method="post">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <div 
                class="parking-slot <?php echo in_array(['parking_slot' => $i], $bookedSlots) ? 'booked' : 'available'; ?>" 
                data-slot="<?php echo $i; ?>"
                onclick="handleSlotClick(<?php echo $i; ?>)"
            >
                <?php echo $i; ?>
            </div>
        <?php endfor; ?>
        <input type="hidden" name="slot" value="">
    </form>
    <a href="customerpage.html" class="home-button">Home</a>
    
    <script>
        function handleSlotClick(slot) {
            var bookedSlots = <?php echo json_encode(array_column($bookedSlots, 'parking_slot')); ?>;
            if (bookedSlots.indexOf(slot) === -1) {
                var form = document.getElementById('bookingForm');
                form.querySelector('input[name="slot"]').value = slot;
                form.submit();
            } else {
                alert('Slot is already booked by another user. Please choose another slot.');
            }
        }
    </script>
</body>
</html>
