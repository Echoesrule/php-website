<?php
session_start();
include("connections.php");
include("navigations.php");

// Check admin
if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] != 1) {
    die("Access denied. Admins only.");
}

// Validate product ID
if (!isset($_GET['id'])) {
    die("No product selected.");
}
$id = (int)$_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0){
    die("Product not found.");
}
$product = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $description = $_POST['description'];

    // Handle image upload if exists
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/subcategories/$imageName");
    } else {
        $imageName = $product['image']; // keep old image
    }

    $update_stmt = $conn->prepare("UPDATE products SET title=?, price=?, stock=?, description=?, image=? WHERE id=?");
    $update_stmt->bind_param("sdisii", $title, $price, $stock, $description, $imageName, $id);

    if($update_stmt->execute()){
        $update_stmt->close();
        header("Location: Admin.php?msg=Product+updated+successfully");
        exit;
    } else {
        die("Error updating product: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link rel="stylesheet" href="../css/edit.css">
</head>
<body>
    <div class="edit">
    <h1>Edit Product</h1>
<form action="" method="POST" enctype="multipart/form-data" class="edit-form">
    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required><br>

    <label>Price</label>
    <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" required><br>

    <label>Stock</label>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" required><br>

    <label>Description</label>
    <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br>

    <label>Image (optional)</label>
    <input type="file" name="image"><br>

    <button type="submit">Update Product</button><br>
</form>
    </div>

<a href="Admin.php">← Back to Dashboard</a>

</body>
</html>
