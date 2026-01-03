<?php
session_start();
include("connections.php");
header('Content-Type: application/json');

$response = ['success'=>false, 'message'=>'', 'cart_count'=>0];

if (!isset($_SESSION['user']['id'])) {
    $response['message'] = "You must log in to manage your cart.";
    echo json_encode($response);
    exit;
}

$user_id = (int)$_SESSION['user']['id'];
$cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
$action = $_POST['action'] ?? '';

if (!$cart_id || !$product_id) {
    $response['message'] = "Invalid request.";
    echo json_encode($response);
    exit;
}

// Get current cart item and product stock
$stmt = $conn->prepare("SELECT c.quantity AS cart_qty, p.stock FROM cart c JOIN products p ON c.product_id=p.id WHERE c.id=? AND c.user_id=?");
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $response['message'] = "Cart item not found.";
    echo json_encode($response);
    exit;
}
$row = $result->fetch_assoc();
$currentQty = $row['cart_qty'];
$currentStock = $row['stock'];
$stmt->close();

if ($action === 'update') {
    // Calculate stock change
    $diff = $quantity - $currentQty;
    
    if ($diff > $currentStock) {
        $response['message'] = "Not enough stock available.";
        echo json_encode($response);
        exit;
    }

    // Update cart
    $update_cart = $conn->prepare("UPDATE cart SET quantity=? WHERE id=? AND user_id=?");
    $update_cart->bind_param("iii", $quantity, $cart_id, $user_id);
    $update_cart->execute();
    $update_cart->close();

    // Update product stock
    $newStock = $currentStock - $diff;
    $update_stock = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
    $update_stock->bind_param("ii", $newStock, $product_id);
    $update_stock->execute();
    $update_stock->close();

    $response['success'] = true;
    $response['message'] = "Cart updated successfully!";
}
elseif ($action === 'remove') {
    // Return stock back
    $newStock = $currentStock + $currentQty;
    $update_stock = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
    $update_stock->bind_param("ii", $newStock, $product_id);
    $update_stock->execute();
    $update_stock->close();

    // Remove from cart
    $delete_cart = $conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $delete_cart->bind_param("ii", $cart_id, $user_id);
    $delete_cart->execute();
    $delete_cart->close();

    $response['success'] = true;
    $response['message'] = "Item removed from cart.";
}

// Return updated cart count
$res = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE user_id=?");
$res->bind_param("i", $user_id);
$res->execute();
$result = $res->get_result();
$total = $result->fetch_assoc()['total'] ?? 0;
$res->close();

$response['cart_count'] = $total;
echo json_encode($response);
?>
