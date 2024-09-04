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

// Fetch data from srilankan_meal table
$sql = "SELECT id, meal_name, meal_price, about, meal_picture FROM srilankan_meal";
$result = $conn->query($sql);

// Handle form submission for adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['meal_id'])) {
    session_start();
    $meal_id = intval($_POST['meal_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!isset($_SESSION['cart'][$meal_id])) {
        $_SESSION['cart'][$meal_id] = 1; // Default quantity of 1
    } else {
        $_SESSION['cart'][$meal_id] += 1; // Increment quantity if item is already in the cart
    }
    echo "<script>
            alert('Your food has been added to the cart');
            window.location.href = 'cart.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sri Lankan Meals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 20px;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
        }
        .meal-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 400px; /* Adjust height for portrait frame */
        }
        .meal-card img {
            width: 100%;
            height: 250px; /* Fixed height for portrait frame */
            object-fit: cover;
            aspect-ratio: 3 / 4; /* Portrait frame */
        }
        .meal-card-content {
            padding: 15px;
        }
        .meal-card h3 {
            margin-top: 0;
        }
        .meal-card .price {
            color: #4CAF50;
            font-size: 1.2em;
        }
        .meal-card .buttons {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .meal-card button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            flex: 1;
            margin-right: 5px;
        }
        .meal-card button.view-info {
            background-color: rgb(75, 44, 11);
        }
        .meal-card button:last-child {
            margin-right: 0;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function showModal(mealPicture, about) {
            var modal = document.getElementById("infoModal");
            var modalContent = document.getElementById("modal-content-text");
            var modalImage = document.getElementById("modal-image");
            modalContent.innerText = about;
            modalImage.src = mealPicture;
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("infoModal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("infoModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $imagePath = $row["meal_picture"];
                echo '<div class="meal-card">';
                if (file_exists($imagePath)) {
                    echo '<img src="' . $imagePath . '" alt="' . $row["meal_name"] . '">';
                } else {
                    echo '<p>Image not found: ' . $imagePath . '</p>';
                }
                echo '<div class="meal-card-content">';
                echo '<h3>' . $row["meal_name"] . '</h3>';
                echo '<p class="price">Price: Rs.' . $row["meal_price"] . '</p>';
                echo '<div class="buttons">';
                echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" style="display:inline-block;">';
                echo '<input type="hidden" name="meal_id" value="' . $row["id"] . '">';
                echo '<button type="submit">Add to Cart</button>';
                echo '</form>';
                echo '<button class="view-info" onclick="showModal(\'' . $imagePath . '\', \'' . addslashes($row["about"]) . '\')">View Info</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No meals found.";
        }
        ?>
    </div>

    <div id="infoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-image" src="" alt="Meal Image" style="width:100%; height:auto; border-radius:10px;">
            <p id="modal-content-text"></p>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
