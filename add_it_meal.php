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
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<script>alert('Sorry, file already exists.');</script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($file["size"] > 500000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
    return null;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $about = $_POST['about'];

    // Validate inputs
    if (empty($meal_name) || empty($meal_price) || empty($about)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else if (!is_numeric($meal_price)) {
        echo "<script>alert('Please enter a valid price.');</script>";
    } else {
        // Handle file upload
        $meal_picture = handleFileUpload($_FILES['meal_picture']);

        // Check if file upload was successful
        if ($meal_picture) {
            // Prepare SQL statement
            $sql = "INSERT INTO italian_meal (meal_name, meal_price, about, meal_picture) VALUES ('$meal_name', '$meal_price', '$about', '$meal_picture')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('New record created successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
            }
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Italian Meal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px;
        }
        .container {
            display: flex;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }
        .form-container {
            flex: 1;
            padding: 20px;
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
        .image-container {
            flex: 1;
            background: url('sign.jpg') no-repeat center center/cover;
            border-radius: 10px;
            margin-right: 20px;
        }
    </style>
    <script>
        function validateForm() {
            const mealName = document.getElementById('meal_name').value;
            const mealPrice = document.getElementById('meal_price').value;
            const about = document.getElementById('about').value;
            const mealPicture = document.getElementById('meal_picture').value;

            if (mealName === '' || mealPrice === '' || about === '' || mealPicture === '') {
                alert('Please fill in all required fields.');
                return false;
            }

            if (isNaN(mealPrice)) {
                alert('Please enter a valid price.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="image-container"></div>
        <div class="form-container">
            <h2>Add Italian Meal</h2>
            <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="meal_name">Meal Name:</label>
                    <input type="text" id="meal_name" name="meal_name" required>
                </div>
                <div class="form-group">
                    <label for="meal_price">Meal Price:</label>
                    <input type="text" id="meal_price" name="meal_price" required>
                </div>
                <div class="form-group">
                    <label for="about">About:</label>
                    <textarea id="about" name="about" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="meal_picture">Meal Picture:</label>
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
