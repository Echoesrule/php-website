<?php
session_start();
include("connections.php");
header('Content-Type: application/json');

$response = ['success'=>false, 'message'=>'', 'cart_count'=>0, 'new_stock'=>null];

if (!isset($_SESSION['user']['id'])) {
    $response['message'] = "You must log in to add items to cart.";
    echo json_encode($response);
    exit;
}

if (!isset($_POST['product_id'], $_POST['quantity'])) {
    $response['message'] = "Invalid request.";
    echo json_encode($response);
    exit;
}

$user_id = (int)$_SESSION['user']['id'];
$product_id = (int)$_POST['product_id'];
$quantity = max(1, (int)$_POST['quantity']);

// Get current stock
$stmt = $conn->prepare("SELECT stock FROM products WHERE id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0){
    $response['message'] = "Product not found.";
    echo json_encode($response);
    exit;
}
$product = $result->fetch_assoc();
$current_stock = (int)$product['stock'];
$stmt->close();

if($quantity > $current_stock){
    $quantity = $current_stock;
}

// Check if item exists in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id=? AND product_id=?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // Update quantity
    $row = $result->fetch_assoc();
    $newQty = $row['quantity'] + $quantity;
    if($newQty > $current_stock) $newQty = $current_stock;

    $update = $conn->prepare("UPDATE cart SET quantity=? WHERE id=? AND user_id=?");
    $update->bind_param("iii", $newQty, $row['id'], $user_id);
    if($update->execute()){
        $response['success'] = true;
        $response['message'] = "Cart updated successfully!";
    } else {
        $response['message'] = "Failed to update cart.";
    }
    $update->close();
}else{
    // Insert new cart row
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $product_id, $quantity);
    if($insert->execute()){
        $response['success'] = true;
        $response['message'] = "Item added to cart!";
    } else {
        $response['message'] = "Failed to add to cart.";
    }
    $insert->close();
}

// Deduct stock in products table
$new_stock = $current_stock - $quantity;
$update_stock = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
$update_stock->bind_param("ii", $new_stock, $product_id);
$update_stock->execute();
$update_stock->close();

$response['new_stock'] = $new_stock;

// Update cart count for nav
$res = $conn->query("SELECT SUM(quantity) AS total FROM cart WHERE user_id=$user_id");
$row = $res->fetch_assoc();
$response['cart_count'] = $row['total'] !== null ? (int)$row['total'] : 0;

echo json_encode($response);
