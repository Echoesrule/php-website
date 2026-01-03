<?php
include("connections.php");
?>
<footer>
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> My Shop. All rights reserved.</p>
        <p>
            <a href="about.php">About Us</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </div>
</footer>
<style>
    /* =========================
   FOOTER STYLING
========================= */
footer {
    width: 100%;
    background-color: #222; /* dark background */
    color: #fff;           /* white text */
    padding: 2rem 1rem;
    text-align: center;
    font-family: 'Poppins', sans-serif;
    margin-top: 3rem;
}

footer a {
    color: #ffd43b;  /* theme yellow */
    text-decoration: none;
    margin: 0 0.5rem;
    transition: color 0.3s;
}

footer a:hover {
    color: #ffc107;  /* lighter hover */
}

footer .footer-content p {
    margin: 0.3rem 0;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    footer {
        padding: 1.5rem 1rem;
        font-size: 0.85rem;
    }

    footer a {
        display: inline-block;
        margin: 0.2rem 0.3rem;
    }
}
</style>
</body>
</html>