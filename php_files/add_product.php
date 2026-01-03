<?php
session_start();
include("connections.php");
include("navigations.php");

// Fetch categories for dropdown
$cats = $conn->query("
    SELECT Categ_id AS id, Name AS name 
    FROM categories 
    ORDER BY Name ASC
")->fetch_all(MYSQLI_ASSOC);

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $subcategory_id = intval($_POST['subcategory_id'] ?? 0);

    // Validate fields
    if ($title === '') $errors[] = "Product title is required.";
    if ($price <= 0) $errors[] = "Price must be greater than 0.";
    if ($stock < 0) $errors[] = "Stock cannot be negative.";
    if ($category_id <= 0) $errors[] = "Please select a category.";
    if ($subcategory_id <= 0) $errors[] = "Please select a subcategory.";

    // Image upload
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image'];
        
        if ($img['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading image.";
        } else {
            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $errors[] = "Invalid image type. Allowed: jpg, jpeg, png, gif.";
            } else {
                $imageName = time() . "_" . bin2hex(random_bytes(6)) . "." . $ext;
                $path = __DIR__ . "/../images/subcategories/" . $imageName;

                if (!move_uploaded_file($img['tmp_name'], $path)) {
                    $errors[] = "Failed to save image.";
                }
            }
        }
    }

    // Insert when no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO products 
            (title, price, stock, description, image, category_id, subcategory_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sdissii",
            $title,
            $price,
            $stock,
            $description,
            $imageName,
            $category_id,
            $subcategory_id
        );

        if ($stmt->execute()) {
            header("Location: Admin.php?added=1");
            exit;
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>
<link rel="stylesheet" href="../css/newprod.css">
</head>
<body>
<div class="product">
<h1>Add New Product</h1>

<?php if (!empty($errors)): ?>
<div style="color:red; margin-bottom:15px;">
    <?php foreach ($errors as $e): ?>
        <div><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <label>Product Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Price (KES)</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Stock</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Category</label><br>
    <select name="category_id" id="category-select" required>
        <option value="">--Select Category--</option>
        <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Subcategory</label><br>
    <select name="subcategory_id" id="subcategory-select" required>
        <option value="">--Select Category First--</option>
    </select><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="5"></textarea><br><br>

    <label>Product Image</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit" class="Add-btn">Add Product</button>

</form>

</div>

<script>
// Load subcategories dynamically
document.getElementById('category-select').addEventListener('change', function () {
    const id = this.value;
    const sub = document.getElementById('subcategory-select');

    if (!id) {
        sub.innerHTML = "<option value=''>--Select Category First--</option>";
        return;
    }

    sub.innerHTML = "<option>Loading...</option>";

    fetch('get_subcat.php?category_id=' + id)
        .then(r => r.json())
        .then(data => {
            sub.innerHTML = "<option value=''>--Select--</option>";
            data.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item.id;
                opt.textContent = item.name;
                sub.appendChild(opt);
            });
        })
        .catch(() => {
            sub.innerHTML = "<option>Error loading subcategories</option>";
        });
});
</script>

</body>
</html>
