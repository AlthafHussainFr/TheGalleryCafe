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

// Function to sanitize input data
function sanitizeInput($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

// Check if delete request is made
if (isset($_POST['delete'])) {
    $id = sanitizeInput($_POST['id']);
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $id);

    if ($deleteStmt->execute()) {
        echo '<script>alert("User deleted successfully.");</script>';
    } else {
        echo "Error: " . $deleteStmt->error;
    }
}

// Check if update user type request is made
if (isset($_POST['update_user_type'])) {
    $id = sanitizeInput($_POST['id']);
    $newUserType = sanitizeInput($_POST['new_user_type']);
    $updateQuery = "UPDATE users SET usertype = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $newUserType, $id);

    if ($updateStmt->execute()) {
        echo '<script>alert("User type updated successfully.");</script>';
    } else {
        echo "Error: " . $updateStmt->error;
    }
}

// Fetch all users from the database
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            text-align: center;
        }

        h1 {
            margin-top: 20px;
            color: #f8f8f8;
        }

        table {
            margin: 20px auto;
            width: 80%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #444;
        }

        th {
            background-color: #555;
        }

        .delete-button, .update-button {
            padding: 5px 10px;
            background-color: #6f4e37;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-button:hover, .update-button:hover {
            background-color: #5a3c1e;
        }

        .update-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .update-form input[type="text"] {
            padding: 5px;
            margin-right: 10px;
            border: 1px solid #555;
            border-radius: 5px;
        }

        .update-form button {
            padding: 5px 10px;
            background-color: #6f4e37;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .update-form button:hover {
            background-color: #5a3c1e;
        }
    </style>
</head>
<body>
    <h1>User Management</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Action</th>
                <th>Update User Type</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($usersResult->num_rows > 0): ?>
                <?php while ($row = $usersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['usertype']; ?></td>
                        <td>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="delete-button">Delete</button>
                            </form>
                        </td>
                        <td>
                            <form action="" method="post" class="update-form">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="new_user_type" placeholder="New User Type" required>
                                <button type="submit" name="update_user_type" class="update-button">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
