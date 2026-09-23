<?php
include 'includes/header.php';
?>

<div style="text-align: center; padding: 2rem 0;">
    <h1>Welcome to Personal Expense Tracker</h1>
    <p style="margin: 1.5rem 0; font-size: 1.2rem; color: #555;">
        Take control of your financial life. Track daily expenses, monitor spendings, and budget smartly.
    </p>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
    <?php else: ?>
        <a href="register.php" class="btn">Get Started (Register)</a>
        <a href="login.php" class="btn" style="background: #3498db; margin-left: 10px;">Sign In</a>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>