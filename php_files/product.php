<?php
session_start();
include("connections.php");
include("navigations.php"); // nav should have <span id="cart-count">

// --------------------------------------------------------------
// 1. Validate product ID
// --------------------------------------------------------------
if (!isset($_GET['id'])) {
    die("No product selected.");
}
$id = (int)$_GET['id'];

// --------------------------------------------------------------
// 2. Fetch product using prepared statement
// --------------------------------------------------------------
$product_stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$product_stmt->bind_param("i", $id);
$product_stmt->execute();
$result = $product_stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$product_stmt->close();


//DISPALAY RELATED ITEMS
$related_products = $conn->query("SELECT * FROM products WHERE subcategory_id = ".$product['subcategory_id']." AND id != ".$product['id']." LIMIT 20");
if(!$related_products){
    die("Related products query failed: ".$conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['title']); ?></title>
<link rel="stylesheet" href="../css/product.css?v=<?= filemtime('../css/product.css'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&family=Poppins:wght@400;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
<!-- PAGE WRAPPER -->
<div class="page-wrapper">

    <!-- BACK LINK -->
    <div class="back-link">
        <a href="home.php">← Back to Home</a>
    </div>

    <!-- PRODUCT SECTION -->
    <div class="product-section">

        <div class="productinfo">
            <h1><?php echo htmlspecialchars($product['title']); ?></h1>

            <div class="product-content">
                <div class="product-image">
                    <img src="../images/subcategories/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['title']); ?>">
                </div>

                <div class="product-details">
                    <p class="price"><strong>Price:</strong> KES <?php echo number_format($product['price'], 2); ?></p>
                    <p class="stock"><strong>Stock:</strong>
                        <span id="product-stock"><?php echo $product['stock']; ?></span>
                    </p>

                    <p class="description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>

                    <!-- ADD TO CART FORM -->
                    <form id="addtocart-form">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="1" min="1"
                               max="<?php echo $product['stock']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>

                    <!-- CART MESSAGE -->
                    <div id="cart-message"></div>
                </div>
            </div>
        </div>
        
</div>
<div class="related-section">
    <h2>You may also like</h2>
    <div class="related-products">
          <?php while($item = $related_products->fetch_assoc()): ?>
            <div class="related-card">
                <img src="../images/subcategories/<?php echo htmlspecialchars($item['image']); ?>" 
                     alt="<?php echo htmlspecialchars($item['title']); ?>">

                <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                <p>KES <?php echo number_format($item['price'], 2); ?></p>

                <a href="product.php?id=<?php echo $item['id']; ?>">View</a>
    </div>
      <?php endwhile; ?>
</div>

</div>

<!-- END PAGE WRAPPER -->

<script>
// Handle Add to Cart via AJAX
const form = document.getElementById('addtocart-form');
const cartMessage = document.getElementById('cart-message');
const productStock = document.getElementById('product-stock');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append('product_id', <?php echo $product['id']; ?>);

    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        cartMessage.textContent = data.message;
        cartMessage.style.color = data.success ? 'green' : 'red';

        // Update cart count in nav
        const cartCount = document.getElementById('cart-count');
        if(cartCount) cartCount.textContent = data.cart_count || 0;

        // Update product stock if success
        if(data.success && data.new_stock !== undefined) {
            productStock.textContent = data.new_stock;
            // Adjust max value of quantity input
            form.querySelector('input[name="quantity"]').max = data.new_stock;
        }
    })
    .catch(err => {
        cartMessage.textContent = "An error occurred.";
        cartMessage.style.color = 'red';
        console.error(err);
    });
});
</script>

<div class="end">
<!-- FOOTER -->
<?php include("footer.php"); ?>

</div>

</body>
</html>
