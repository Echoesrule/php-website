<?php
include("connections.php");
include("navigations.php");
//searching items in terms of categories sub categories and products
$keyword = isset($_GET['search']) ? trim($_GET['search']) : "";
$products = [];
if ($keyword !== "") {
    $like_keyword = '%' . $conn->real_escape_string($keyword) . '%';
    $stmt = $conn->prepare("
        SELECT * FROM products 
        WHERE title LIKE ? 
           OR description LIKE ? 
           OR subcategory_id IN (
               SELECT id FROM subcategories WHERE name LIKE ?
           ) 
           OR subcategory_id IN (
               SELECT id FROM subcategories WHERE category_id IN (
                   SELECT id FROM categories WHERE name LIKE ?
               )
           )
             
    ");
    $stmt->bind_param("ssss", $like_keyword, $like_keyword, $like_keyword, $like_keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
}
?>














<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Results</title>
<link rel="stylesheet" href="../css/search.css">
</head>
<body>

<div class="search-container">
    <?php if ($keyword === ""): ?>
        <h2>Please enter a search term</h2>
    <?php else: ?>
        <h2>Results for "<span><?= htmlspecialchars($keyword) ?></span>"</h2>

        <?php if (empty($products)): ?>
            <p class="info">No related products found.</p>
        <?php else: ?>
            <div class="products">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="../images/subcategories/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                        <h3><?= htmlspecialchars($product['title']) ?></h3>
                        <p class="price">KES <?= number_format($product['price'], 2) ?></p>
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn">View</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
