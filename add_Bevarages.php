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

// Function to handle file upload
function handleFileUpload($file) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($file["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    return null;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $about = $_POST['about'];

    // Handle file upload
    $meal_picture = handleFileUpload($_FILES['meal_picture']);

    // Prepare SQL statement
    $sql = "INSERT INTO bevarages (meal_name, meal_price, about, meal_picture) VALUES ('$meal_name', '$meal_price', '$about', '$meal_picture')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Beverages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            width: 800px;
            margin: auto;
        }
        .image-container {
            flex: 1;
            margin-right: 20px;
        }
        .form-container {
            flex: 1;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 5px;
        }
        .form-group input[type="file"] {
            padding-top: 6px;
        }
        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        .form-group button[type="reset"] {
            background-color: #f44336;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="sign.jpg" alt="Beverage Image">
        </div>
        <div class="form-container">
            <h2>Add Beverages</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="meal_name">Beverage Name:</label>
                    <input type="text" id="meal_name" name="meal_name" required>
                </div>
                <div class="form-group">
                    <label for="meal_price">Beverage Price:</label>
                    <input type="text" id="meal_price" name="meal_price" required>
                </div>
                <div class="form-group">
                    <label for="about">About:</label>
                    <textarea id="about" name="about" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="meal_picture">Beverage Picture:</label>
                    <input type="file" id="meal_picture" name="meal_picture" accept="image/*" required>
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                    <button type="reset">Reset</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
