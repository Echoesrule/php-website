<?php
session_start();
include("connections.php");
include("navigations.php");

// Force browser to ignore cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Fetch all categories
$categoryQuery = $conn->query("SELECT * FROM categories ORDER BY Name ASC");
if(!$categoryQuery){
    die("Categories query failed: ".$conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/style.css?v=<?= filemtime('../css/style.css'); ?>">
<title>Home</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&family=Poppins:wght@400;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
  <div class="slider">

  <div class="slides">
  <div class="slide backgr1">
    <div class="content">
    <h1>Welcome to our shop</h1>
    <p>Shopping made easier ,smatter ,and better.Just cart it!</p><br><br>
    <a href="#shop" class="hero-btn">Browse Categories</a>

    </div>
  </div>  
  <div class="slide backgr2">
    <div class="content">
     <h1>Latest Arrivals</h1>
        <p>Stay ahead with the newest products in our collection.</p><br><br>
    <a href="#shop" class="hero-btn">Get product</a>

    </div>
  </div>
  <div class="slide backgr3">
    <div class="content">
        <h1>Exclusive Offers</h1>
        <p>Grab limited-time discounts and special deals today!</p><br><br>
    <a href="#shop" class="hero-btn">Offers</a>

    </div>
  </div>


  </div>
</div>
<script>
const slides = document.querySelector(".slides");
const slideCount = slides.children.length;
let index = 0;

// Function to move slides
function goToSlide(i) {
    slides.style.transition = 'transform 0.5s ease-in-out';
    slides.style.transform = `translateX(-${i * 100}%)`;
}

// Auto-slide every 5 seconds
let slideInterval = setInterval(() => {
    index++;
    if (index >= slideCount) index = 0;
    goToSlide(index);
}, 5000);

// Optional: Pause on hover
const slider = document.querySelector(".slider");
slider.addEventListener("mouseenter", () => clearInterval(slideInterval));
slider.addEventListener("mouseleave", () => {
    slideInterval = setInterval(() => {
        index++;
        if (index >= slideCount) index = 0;
        goToSlide(index);
    }, 5000);
});

// Handle window resize (slides stay correct)
window.addEventListener("resize", () => {
    goToSlide(index);
});
</script>

<section class="shop-intro" id="shop">
  <h2>Shop by Category</h2>
  <p>Click a category to explore available items</p>
</section>

<div class="categories">
<?php while($cat = $categoryQuery->fetch_assoc()): ?>
    <div class="category" data-cat-id="<?= $cat['Categ_id'] ?>">
        <!-- Display category image if exists -->
        <?php if(!empty($cat['image']) && file_exists("../images/categories/".$cat['image'])): ?>
            <img src="../images/categories/<?= $cat['image'] ?>" alt="<?= htmlspecialchars($cat['Name']) ?>">
        <?php else: ?>
            <img src="images/default.jpg" alt="<?= htmlspecialchars($cat['Name']) ?>">
        <?php endif; ?>
        <h2><?= htmlspecialchars($cat['Name']) ?></h2>

        <ul class="subcategories"></ul>
    </div>
<?php endwhile; ?>
</div>

<script>
// When a category is clicked, fetch and show its subcategories
document.querySelectorAll('.category').forEach(catDiv => {
    catDiv.addEventListener('click', function() {
        const catId = this.dataset.catId;
        const subList = this.querySelector('.subcategories');

        // Toggle visibility if already loaded
        if(subList.style.display === 'block') {
            subList.style.display = 'none';
            return;
        }

        // Fetch subcategories from the server
        fetch('get_subcat.php?category_id=' + catId)
            .then(res => res.json())
            .then(data => {
                subList.innerHTML = '';
                if(data.length === 0) {
                    subList.innerHTML = '<li>No subcategories found</li>';
                } else {
                    data.forEach(sub => {
                        const li = document.createElement('li');
                        li.innerHTML = `<a href="subcategory.php?sub_id=${sub.id}">${sub.name}</a>`;
                        subList.appendChild(li);
                    });
                }
                subList.style.display = 'block';
            })
            .catch(err => {
                subList.innerHTML = '<li>Error loading subcategories</li>';
                subList.style.display = 'block';
                console.error(err);
            });
    });
});
</script>
<footer class="site-footer">
  <div class="footer-container">

    <div class="footer-column">
      <h3>About Us</h3>
      <p>
        We are your one-stop shop for school items, electronics, groceries,
        fashion, toys, and more. Simple. Fast. Reliable.
      </p>
    </div>

    <div class="footer-column">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="#">Categories</a></li>
        <li><a href="#">My Account</a></li>
        <li><a href="cart.php">Cart</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>Customer Care</h3>
      <ul>
        <li><a href="#">Help Center</a></li>
        <li><a href="#">Returns</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms & Conditions</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>Contact</h3>
      <p>Email: kemboimmanueli@gmail.com</p>
      <p>Phone: +254 114581500</p>
      <p>Location: Kenya</p>
    </div>

  </div>

  <div class="footer-bottom">
    <p>&copy; <?= date("Y") ?> . All rights reserved.</p>
  </div>
</footer>

</body>
</html>
