<?php
session_start();
include("connections.php");
include("navigations.php");

if (!isset($_SESSION['user']['id'])) {
    die("Please log in to view your orders.");
}

$user_id = (int)$_SESSION['user']['id'];

// Fetch orders
$stmt = $conn->prepare("
    SELECT o.id AS order_id, o.order_number, o.total_price, o.ordered_at
    FROM orders o
    WHERE o.client_id = ?
    ORDER BY o.ordered_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>
<link rel="stylesheet" href="../css/orders.css">
</head>
<body>
<h1>My Orders</h1>

<div class="orders">
<?php if(empty($orders)): ?>
    <p>You have not placed any orders yet.</p>
<?php else: ?>
   
        
    <?php foreach($orders as $order): ?>
         <div class="order">
            <div class="details">
        <h3>Order <?= $order['order_number'] ?> <br>
         Total: KES <span class="price">
        <?= number_format($order['total_price'],2) ?> <br>
  </span>
          Date ordered:<?= $order['ordered_at'] ?></h3>
            </div>


        <?php
        $stmt = $conn->prepare("
            SELECT oi.quantity, oi.price, p.title
            FROM ordered_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $order['order_id']);
        $stmt->execute();
        $items_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        ?>
    
        <?php foreach($items_result as $item): ?>
            <table>
                <tr>
                      <th>Ordered item:</th>
                <th> Quantity:</th>
                <th> Price:</th>
                </tr>
              <tr>
                  <td><?= htmlspecialchars($item['title']) ?>   </td>
                <td><?= $item['quantity'] ?>  </td> 
                <td><?= number_format($item['price'],2) ?></td>
              </tr>
              

            </table>
        <?php endforeach; ?>
     
    </div>

    <?php endforeach; ?>

<?php endif; ?>
    </div>


</body>
</html>
