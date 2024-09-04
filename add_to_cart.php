<?php
session_start();

// Initialize cart if not set or is unset
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get meal ID from POST request
$meal_id = $_POST['meal_id'];

// Check if the meal is already in the cart
if (!in_array($meal_id, $_SESSION['cart'])) {
    $_SESSION['cart'][] = $meal_id;
}

header("Location: cart.php");
exit();
?>
