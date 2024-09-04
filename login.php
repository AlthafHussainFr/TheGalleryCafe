<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thegallerycafe";

// Establishing connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Handling registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    
    // Hashing passwords before insertion
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Inserting data into users table
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Registration successful! Please log in.")</script>';
    } else {
        echo '<script>alert("Error: ' . $sql . '<br>' . $conn->error . '")</script>';
    }
}

// Handling login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    
    // Retrieving hashed password from database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verifying password
        if (password_verify($password, $hashed_password)) {
            echo '<script>alert("Login successful! Welcome back.")</script>';
        } else {
            echo '<script>alert("Login failed. Invalid credentials.")</script>';
        }
    } else {
        echo '<script>alert("Login failed. User not found.")</script>';
    }
}

// Close MySQL connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            height: 100%;
            font-size: 62.5%;
            font-family: Montserrat, sans-serif;
        }
        
        body {
            height: 100%;
            background: #e9ebee;
            color: #1d2129;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }
        
        #container {
            width: 95%;
            max-width: 800px;
            height: 500px;
            position: relative;
            border-radius: 20px;
            box-shadow: 0 14px 28px -10px rgba(0, 0, 0, .1), 0 10px 10px -10px rgba(0, 0, 0, .02);
            transform-style: preserve-3d;
            overflow: hidden;
        }
        
        #container > div {
            position: absolute;
            width: 50%;
            min-width: 350px;
            height: 100%;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .content {
            width: 100%;
            padding: 2em 4em;
            text-align: center;
        }
        
        .login {
            left: 0;
            background: #fafafa;
            border-radius: 20px 0 0 20px;
        }
        
        .login button {
            border-radius: 0;
            width: 100%;
        }
        
        .register {
            right: 0;
            z-index: 1;
            border-radius: 0 20px 20px 0;
            background: #fafafa;
        }
        
        .register button {
            border-radius: 0;
            width: 100%;
        }
        
        .page {
            right: 0;
            color: #fff;
            border-radius: 0 20px 20px 0;
            transform-origin: left center;
            transition: animation 1s linear;
        }
        
        .page button {
            border-color: #fff;
            background: transparent;
        }
        
        .page p {
            margin: 2em auto;
        }
        
        .front {
            background: linear-gradient(-45deg, #ffcf00 0%, #fc4f4f 100%) no-repeat 0 0 / 200%;
            z-index: 3;
        }
        
        .back {
            background: linear-gradient(135deg, #fc4f4f 0%, #ffcf00 100%) no-repeat 0 0 / 200%;
            z-index: 2;
        }
        
        .active .front {
            animation: rot-front .6s ease-in-out forwards;
        }
        
        .active .back {
            animation: rot-back .6s ease-in-out forwards;
        }
        
        .close .front {
            animation: close-rot-front .6s ease-in-out forwards;
        }
        
        .close .back {
            animation: close-rot-back .6s ease-in-out forwards;
        }
        
        @keyframes rot-front {
            from {
                transform: translateZ(2px) rotateY(0deg);
            }
            to {
                transform: translateZ(1px) rotateY(-180deg);
            }
        }
        
        @keyframes close-rot-front {
            from {
                transform: translateZ(1px) rotateY(-180deg);
            }
            to {
                transform: translateZ(2px) rotateY(0deg);
            }
        }
        
        @keyframes rot-back {
            from {
                transform: translateZ(1px) rotateY(0deg);
            }
            to {
                transform: translateZ(2px) rotateY(-180deg);
            }
        }
        
        @keyframes close-rot-back {
            from {
                transform: translateZ(2px) rotateY(-180deg);
            }
            to {
                transform: translateZ(1px) rotateY(0deg);
            }
        }
        
        .active .register .content {
            animation: show .7s ease-in-out forwards;
        }
        
        .close .register .content {
            animation: hide .7s ease-in-out forwards;
        }
        
        .active .login .content {
            animation: hide .7s ease-in-out forwards;
        }
        
        .close .login .content {
            animation: show .7s ease-in-out forwards;
        }
        
        @keyframes show {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes hide {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.8);
            }
        }
        
        .loginwith {
            display: block;
            width: 100%;
            margin-top: 1em;
            white-space: nowrap;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .loginwith::before {
            content: "";
            display: inline-block;
            width: 42%;
            height: 1px;
            background: #aaa;
            vertical-align: middle;
            margin-right: 5%;
        }
        
        .loginwith::after {
            content: "";
            display: inline-block;
            width: 45%;
            height: 1px;
            background: #aaa;
            vertical-align: middle;
            margin-left: 5%;
        }
        
        .remember::before {
            content: "";
            display: inline-block;
            width: 1em;
            height: 1em;
            border: 2px solid #999;
            vertical-align: top;
            margin-right: 4px;
        }
        
        .forget {
            float: right;
        }
        
        .clearfix {
            clear: both;
            display: table;
        }
        
        .copy {
            display: block;
            position: absolute;
            bottom: 0;
            font-size: 1em;
        }
        
        button {
            display: block;
            margin: 1em auto;
            border-radius: 40px;
            border: 1px solid #ff4b2b;
            background: #ff4b2b;
            color: #fff;
            font-size: 1.2em;
            font-weight: bold;
            padding: .8em 2em;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
            outline: none;
        }
        
        button:hover {
            transform: scale(0.95);
        }
        
        input {
            background: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            font-size: 1.6rem;
            outline: none;
            text-align: center; /* Center text inside inputs */
        }
        
        h1 {
            font-weight: 700;
            font-size: 3.5em;
            text-align: center;
            margin-bottom: 1em;
        }
        
        p {
            font-size: 1.6rem;
            margin-bottom: 2em;
        }
    </style>
</head>
<body>

<div id="container">
    <div class="login">
        <div class="content">
            <h1>Log In</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Log In</button>
            </form>
            <span class="loginwith">Or Connect with</span>
            <!-- Social media icons -->
            <a href="https://www.facebook.com/emin.qasimovdia"><svg>...</svg></a>
            <a href="https://www.twitter.com/webkoder"><svg>...</svg></a>
            <a href="https://www.github.com/eminqasimov"><svg>...</svg></a>
            <a href="#"><svg>...</svg></a>
            <span class="copy">&copy; 2019</span>
        </div>
    </div>
    <div class="page front">
        <div class="content">
            <h1>Hello, friend!</h1>
            <p>Enter your personal details and start your journey with us</p>
            <button type="button" id="register">Register <svg>...</svg></button>
        </div>
    </div>
    <div class="page back">
        <div class="content">
            <h1>Welcome Back!</h1>
            <p>To keep connected with us please login with your personal info</p>
            <button type="button" id="login"><svg>...</svg> Log In</button>
        </div>
    </div>
    <div class="register">
        <div class="content">
            <h1>Sign Up</h1>
            <span class="loginwith">Or</span>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <span class="remember">I accept terms</span>
                <button type="submit" name="register">Register</button>
            </form>
        </div>
    </div>
</div>

<a href="https://codepen.io/eminqasimov/full/KYrVBr" target="_blank" class="version">Rolling Version</a>

<script>
    const registerButton = document.getElementById('register');
    const loginButton = document.getElementById('login');
    const container = document.getElementById('container');

    registerButton.onclick = function(){
        container.className = 'active';
    };
    loginButton.onclick = function(){
        container.className = 'close';
    };
</script>

</body>
</html>
