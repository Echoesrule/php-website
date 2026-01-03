<?php
session_start();
include("connections.php");
include("navigations.php");

if (!isset($_SESSION['user']['id'])) {
    die("Please log in to view your cart.");
}

$user_id = (int)$_SESSION['user']['id'];

// Fetch cart items with product info
$stmt = $conn->prepare("
    SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.title, p.price, p.stock
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Cart</title>
<link rel="stylesheet" href="../css/cart.css">
</head>
<body>
<div class="cart-container">
<h1>Your Cart</h1>
<div class="cart-table">
    <table border="1" cellpadding="10">
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Action</th>
</tr>
<?php foreach ($cart_items as $item): ?>
<tr data-cart-id="<?= $item['cart_id'] ?>" data-product-id="<?= $item['product_id'] ?>" data-stock="<?= $item['stock'] ?>" data-price="<?= $item['price'] ?>">
    <td><?= htmlspecialchars($item['title']) ?></td>
    <td><?= number_format($item['price'], 2) ?></td>
    <td>
        <button class="dec-btn">-</button>
        <span class="qty"><?= $item['quantity'] ?></span>
        <button class="inc-btn">+</button>
    </td>
    <td class="row-total"><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
    <td><button class="remove-btn">Remove</button></td>

</tr>
<?php endforeach; ?>
</table>
</div>

<h3>Total: KES <span id="cart-total">

<?php
$total = 0;
foreach ($cart_items as $item)
$total += $item['price'] * $item['quantity'];
echo number_format($total, 2);
?>
</span></h3>

    <button id="checkout_btn">Check out</button>
<div id="checkout-message" style="margin-top:10px; font-weight:bold;"></div>

<div id="cart-message"></div>
</div>


<script>
const messageEl = document.getElementById('cart-message');//to display error or succes message


//decreasing and increasing buttons
document.querySelectorAll('.inc-btn, .dec-btn, .remove-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = this.closest('tr');
        const cartId = row.dataset.cartId;
        const productId = row.dataset.productId;
        let qty = parseInt(row.querySelector('.qty').textContent);
        const stock = parseInt(row.dataset.stock);

        let action = '';

        if (this.classList.contains('inc-btn')) {
            if (qty < stock) { 
                qty++; 
                action = 'update'; 
            } else { 
                messageEl.textContent = 'Cannot exceed stock (' + stock + ').'; 
                messageEl.style.color = 'red';
                return; 
            }
        }
        else if (this.classList.contains('dec-btn')) {
            if (qty > 1) { 
                qty--; 
                action = 'update'; 
            } else return;
        }
        else if (this.classList.contains('remove-btn')) {
            action = 'remove';
        }

        const formData = new FormData();
        formData.append('cart_id', cartId);
        formData.append('product_id', productId);
        formData.append('quantity', qty);
        formData.append('action', action);

        fetch('update_cart.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            messageEl.textContent = data.message;
            messageEl.style.color = data.success ? 'green' : 'red';
            if (data.success) {
                if (action === 'remove') row.remove();
                else row.querySelector('.qty').textContent = qty;

                // Update total for this row
                const totalCell = row.querySelector('td:nth-child(4)');
                const price = parseFloat(row.querySelector('td:nth-child(2)').textContent);
                totalCell.textContent = (price * qty).toFixed(2);

                // Update overall cart count
                const cartCount = document.getElementById('cart-count');
                if(cartCount) cartCount.textContent = data.cart_count;

                // Update overall cart total
                const allRows = document.querySelectorAll('table tr[data-cart-id]');
                let grandTotal = 0;

                allRows.forEach(row => {
                    const price = parseFloat(row.dataset.price);
                    const quantity= parseInt(row.querySelector('.qty').textContent);
                    grandTotal += price * quantity;
                });

               document.getElementById('cart-total').textContent=grandTotal.toFixed(2);
            }
        })
        .catch(err => console.error(err));
    });
});

//user checks out 

document.getElementById('checkout_btn').addEventListener('click', () => {
    fetch('checkout.php', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById('checkout-message');

        msg.textContent = data.message;
        msg.style.color = data.success ? "green" : "red";

        if (data.success) {
            // Remove all rows
            document.querySelectorAll("tr[data-cart-id]").forEach(r => r.remove());

            // Update totals
            document.getElementById('cart-total').textContent = "0.00";

            // Update nav cart count
            const navCount = document.getElementById('cart-count');
            if (navCount) navCount.textContent = "0";
        }
    })
    .catch(err => console.error(err));
});


</script>


</body>
</html>
