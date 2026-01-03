<?php
session_start();
include("connections.php");

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category'] ?? 0);

    if ($category_id <= 0) {
        $errors[] = "Please select a category.";
    }

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading image.";
        } 
        else {
            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $errors[] = "Invalid image type. Allowed: jpg, jpeg, png, gif.";
            } 
            else {
                $imageName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $destination = __DIR__ . "/images/categories/" . $imageName;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    $errors[] = "Failed to save image.";
                } 
                else {
                    // Update database
                    $stmt = $conn->prepare("UPDATE categories SET image=? WHERE Categ_id=?");
                    $stmt->bind_param("si", $imageName, $category_id);
                    
                    if ($stmt->execute()) {
                        $success = true;
                    }
                     else {
                        $errors[] = "Database error: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
        }
    } else {
        $errors[] = "Please select an image to upload.";
    }
}

// Fetch categories
$cats = $conn->query("SELECT Categ_id AS id, Name AS name FROM categories ORDER BY Name ASC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Category Image</title>
</head>
<body>

<h1>Upload Category Image</h1>

<?php if ($success): ?>
    <div style="color:green;">Image uploaded successfully!</div>
<?php endif; ?>

<?php if ($errors): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Select Category</label><br>
    <select name="category" required>
        <option value="">--Select--</option>
        <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Choose Image</label><br>
    <input type="file" name="image" accept="image/*" required><br><br>

    <button type="submit">Upload</button>
</form>

</body>
</html>

