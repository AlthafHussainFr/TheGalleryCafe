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

// Fetch data from  table
$sql = "SELECT id, meal_name, meal_price, about, meal_picture FROM chinese_meal";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Lover</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        


@import url("https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap");
/* Base */
:root {
  --clr-primary: rgb(75, 44, 11);
  --clr-secondary: rgb(75, 44, 11);
}

*,
*::after,
*::before {
  box-sizing: border-box;
  padding: 0;
  margin: 0;
  font-family: "Josefin Sans", sans-serif;
}

html {
  scroll-behavior: smooth;
}

body {
  background: #fff;
  color: var(--clr-secondary);
  overflow-x: hidden;
}

.section {
  width: 100%;
  padding: 40px 0;
}

.flex {
  display: flex;
  align-items: center;
  justify-content: center;
}

.container {
  padding: 0 30px;
}

.primary {
  font-size: 64px;
  font-weight: 700;
  margin-bottom: 20px;
}

.secondary {
  font-size: 44px;
  font-weight: 700;
  margin-bottom: 20px;
}

.tertiary {
  font-size: 24px;
  font-weight: 400;
  margin-bottom: 20px;
}

.btn {
  padding: 14px 20px;
  background-color: rgb(75, 44, 11);
  border-radius: 4px;
  color: #fff;
  text-decoration: none;
  font-size: 22px;
  display: inline-block;
  margin: 20px 0;
}

.btn:hover{
  background-color: black;
  color:white;
}
/* End Base */

/* Menu */
nav {
  height: 80px;
  width: 100%;
  position: fixed;
  top: 0;
  background: #fff5f2;
  box-shadow: 0 1px 1px -1px rgba(0, 0, 0, 0.22);
  z-index: 22;
}


label.logo {
  font-size: 35px;
  line-height: 80px;
  padding: 0 30px;
  font-weight: 700;
}

nav ul {
  float: right;
  margin-right: 20px;
}

nav ul li {
  display: inline-block;
  line-height: 80px;
  margin: 0 5px;
}

nav ul li a {
  font-size: 18px;
  padding: 7px 13px;
  text-decoration: none;
  color: var(--clr-secondary);
}

.menu a.active,
.menu a:hover {
  border-bottom: 2px solid var(--clr-primary);
  transition: 0.3s;
}

.checkbtn {
  font-size: 30px;
  color: #fff;
  float: right;
  line-height: 80px;
  margin-right: 40px;
  cursor: pointer;
  display: none;
}

#check {
  display: none;
}
/* End Menu */


/* Footer */
.footer {
  background: var(--clr-secondary);
  color: #b2b2b2;
  padding: 30px 0;
}

.footer .container {
  justify-content: space-between;
  align-items: flex-start;
}

.footer h2 {
  font-size: 22px;
  margin-bottom: 10px;
  color: #fff;
}

.footer ul {
  list-style: none;
  line-height: 30px;
  font-size: 16px;
}

.footer ul li {
  cursor: pointer;
}

.footer-about {
  width: 35%;
}

.copyright {
  text-align: center;
  padding: 20px 0;
  margin-top: 30px;
  border-top: 1px solid #b2b2b2;
}
/* End Footer */

/* Responsive  */
@media (max-width: 952px) {
  label.logo {
    font-size: 30px;
    padding-left: 20px;
  }

  nav ul li a {
    font-size: 16px;
  }
}

@media (max-width: 858px) {
  .checkbtn {
    display: block;
    color: var(--clr-primary);
  }

  ul {
    position: fixed;
    width: 100%;
    height: 100vh;
    top: 80px;
    left: -100%;
    top: 80px;
    transition: all 0.5s;
    text-align: center;
    background: #fff5f2;
  }

  nav ul li {
    display: block;
    margin: 50px 0;
    line-height: 30px;
  }

  nav ul li a {
    font-size: 20px;
  }

  .menu a:hover,
  .menu a.active {
    background: none;
    color: var(--clr-primary);
  }

  #check:checked ~ ul {
    left: 0;
  }

  #hero-section {
    margin-top: 80px;
  }

  #hero-section .text {
    margin-left: auto;
  }

  .flex {
    flex-direction: column;
  }

  .visual,
  .text {
    width: 70%;
    margin: 15px auto;
    text-align: center;
  }

  .download,
  .user {
    flex-direction: row;
  }

  .user {
    justify-content: center;
  }

  .visual img {
    margin: 0 auto;
  }

  .box {
    margin: 15px 0;
  }

  .restaurant-menu {
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 20px;
  }

  .faq,
  .menu-item {
    width: 80%;
  }

  .primary {
    font-size: 56px;
  }

  .secondary {
    font-size: 40px;
  }

  .tertiary {
    font-size: 20px;
  }

  .footer-about {
    width: 100%;
    text-align: center;
  }

  .quick-links,
  .get-in-touch,
  .footer-category {
    display: none;
  }
}

@media (max-width: 680px) {
  .container {
    padding: 0 10px;
  }

  .text,
  .visual {
    width: 90%;
  }

  .restaurant-menu {
    grid-template-columns: 1fr;
  }

  .faq {
    width: 90%;
  }

  .app-store {
    margin: 10px 0;
  }

  .download {
    flex-direction: column;
  }

  .primary {
    font-size: 48px;
  }

  .secondary {
    font-size: 32px;
  }

  .tertiary {
    font-size: 17px;
  }
}
/* End Responsive  */





.youtube {
  position: fixed;
  bottom: 40px;
  right: 70px;
  text-decoration: none;
  padding: 8px 12px;
  background: rgba(0,0,0,0.6);
  border-radius: 6px;
  box-shadow: 0 2px 2px 3px rgba(0, 0, 0, 0.2);
  color: #fff;
}

.youtube p {
  font-size: 22px;
}










        /* Container for the meal cards */
        .meal-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Individual meal card */
        .meal-card {
            flex: 1 1 calc(33.333% - 20px); /* Three items per row */
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            text-align: center;
            padding: 10px;
            max-width: 300px; /* Set a max width for consistent sizing */
        }

        .meal-card img {
            width: 100%;
            height: 200px; /* Set height for consistent sizing */
            object-fit: cover; /* Ensure images cover the frame */
            border-bottom: 1px solid #ddd;
        }

        .meal-card-content {
            padding: 10px;
        }

        .meal-card-content h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .meal-card-content .price {
            font-size: 16px;
            color: #fe5722;
        }

        .meal-card-content .buttons {
            margin-top: 10px;
        }

        .meal-card-content button {
            background-color: rgb(75, 44, 11);
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .meal-card-content button:hover {
            background-color: #e64a19;
        }

        .view-info {
            background-color: #007bff;
        }

        .view-info:hover {
            background-color: #0056b3;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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

        /* Title container styling */
        .title-container {
            text-align: center; /* Center the text */
            margin: 20px 0; /* Add margin for spacing */
        }

        /* Title styling */
        .title-container h1 {
            font-size: 36px; /* Adjust font size as needed */
            color: #333; /* Choose a color that fits your design */
            margin: 0; /* Remove default margin */
            padding: 10px 0; /* Add padding for spacing */
            border-bottom: 2px solid #fe5722; /* Optional: Add a border under the title for emphasis */
        }
    </style>
</head>
<body>
    <!-- Menu -->
    <div class="menu">
        <nav>
            <input type="checkbox" id="check" />
            <label for="check" class="checkbtn">
                <ion-icon name="grid-outline"></ion-icon>
            </label>

            <label class="logo">The Gallery Cafe</label>

            <ul>
          <li><a href="customerpage.html" class="active">Home</a></li>
          <li><a href="customerpage.html#about">About</a></li>
        
          
       
          <li><a href="sl.php">Srilankan Meals</a></li>
          <li><a href="it.php">Italian Meals</a></li>
          <li><a href="cn.php">Chinese Meals</a></li>
          <li><a href="bv.php">Bevarages</a></li>
          <li><a href="cview.php">orders</a></li>
          <li><a href="index.html">Logout</a></li>
        </ul>
        </nav>
    </div>
    <!-- End Menu -->

    <!-- Hero Section -->
    <div class="container">
        <!-- Title Section -->
        <div class="title-container">
            <h1>Sri Lankan Menu Foods</h1>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                <input type="text" name="search" placeholder="Search by title..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Meal Grid -->
        <div class="meal-grid">
            <?php
            // Apply search filter if set
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $sql = "SELECT id, meal_name, meal_price, about, meal_picture FROM chinese_meal WHERE meal_name LIKE '%$search%'";
            $result = $conn->query($sql);

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
    </div>

    <div id="infoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-image" src="" alt="Meal Image" style="width:100%; height:auto; border-radius:10px;">
            <p id="modal-content-text"></p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container flex">
            <div class="footer-about">
                <h2>About</h2>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime
                    aspernatur sit deleniti enim voluptas voluptatum incidunt rerum,
                    exercitationem voluptate nemo quo impedit ad perspiciatis tempore
                    nulla dolore fugit, fuga eos.
                </p>
            </div>

            <div class="footer-category">
                <h2>Our Menu</h2>
                <ul>
                    <li>Biryani</li>
                    <li>Chicken</li>
                    <li>Pizza</li>
                    <li>Burger</li>
                    <li>Pasta</li>
                </ul>
            </div>

            <div class="quick-links">
                <h2>Quick Links</h2>
                <ul>
                    <li>About Us</li>
                    <li>Contact Us</li>
                    <li>Menu</li>
                    <li>Order</li>
                    <li>Services</li>
                </ul>
            </div>

            <div class="get-in-touch">
                <h2>Get in touch</h2>
                <ul>
                    <li>Account</li>
                    <li>Support Center</li>
                    <li>Feedback</li>
                    <li>Suggestion</li>
                </ul>
            </div>
        </div>

        <div class="copyright">
            <p>Copyright &copy; 2024. All Rights Reserved by Yathusha</p>
        </div>
    </div>
    <!-- End Footer -->

    <!-- Ion Icons Js -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
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
</body>
</html>
