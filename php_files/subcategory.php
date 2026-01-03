<?php
include("connections.php");
include("navigations.php");


if(!isset($_GET['sub_id'])){
    die("No subcategory selected");
}

$sub_id = intval($_GET['sub_id']);

// Fetch subcategory info
$subQuery = $conn->query("SELECT * FROM subcategories WHERE id=$sub_id");
if(!$subQuery || $subQuery->num_rows==0){
    die("Subcategory not found");
}
$subCategory = $subQuery->fetch_assoc();

// Fetch products
$productQuery = $conn->query("SELECT * FROM products WHERE subcategory_id=$sub_id");
if(!$productQuery){
    die("Products query failed: ".$conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $subCategory['name']; ?></title>
<link rel="stylesheet" href="../css/subcategory.css?v=<?= filemtime('../css/subcategory.css'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&family=Poppins:wght@400;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">             
</head>
<body>
    <div class="intro">
<a href="home.php">Back to Home</a>
    <h1>Products in <?php echo $subCategory['name']; ?></h1>

    <p>Explore our exclusive range of products in the <?php echo $subCategory['name']; ?> subcategory. Find the best deals and latest trends tailored just for you!</p>
    </div>

<div class="products-container">
<?php
if($productQuery->num_rows > 0){
    while($product = $productQuery->fetch_assoc()){
        echo "<div class='product-card'>";
        echo "<img src='../images/subcategories/".$product['image']."' alt='".$product['title']."'>";
        echo"<div class='details'>";
        echo "<h3><strong>Title:</strong>".$product['title']."</h3>";
        echo "<p><strong>Price:</strong>KES ".number_format($product['price'],2)."</p>";
        echo "<p><strong>Description:</strong> ".$product['description']."</p>";
        echo"</div>";
        echo "<a href='product.php?id=".$product['id']."'>View Details</a>";
        echo "</div>";
    }
}
//Checks if there are products on the sub category 
else{
echo "<div class='no-item'>";
echo "<h3>Ooops nothing here!!.</h3>";
echo"</div>";
}
?>
</div>
</body>
</html>
