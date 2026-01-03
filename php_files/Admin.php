<?php
session_start();
include("connections.php");
include("navigations.php");

// Redirect non-admin users
if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] != 1) {
    die("Access denied. Admins only.");
}

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="update">
<h1>Admin Dashboard</h1>
<a href="add_product.php" class="new-prod">+ Add New Product</a>
    </div>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Actions</th>
</tr>
<?php foreach($products as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td>
    <div class="title">
        <?= htmlspecialchars($p['title']) ?>
    </div>
    </td>

    <td><?= number_format($p['price'], 2) ?></td>
    <td><?= $p['stock'] ?></td>
    <td>

    <div class="actions">
        <a href="edit_product.php?id=<?= $p['id'] ?>">Edit</a>

<form action="delete.php" method="POST" onsubmit="return confirm('Delete this product?')">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">
    <button type="submit" class="delete-btn">Delete</button>
</form>
   

    </div>


</td>
 
</tr>
<?php endforeach; ?>
</table>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.dataset.id;
        if(confirm("Delete this product?")){
            fetch(`delete_product.php?id=${id}`)
                .then(res => res.text())
                .then(() => {
                    // Remove row from table
                    button.closest('tr').remove();
                })
                .catch(err => alert("Error deleting product."));
        }
    });
});
</script>

</body>
</html>
