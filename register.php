<?php
require_once 'config/db.php';
include 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($fullname) && !empty($username) && !empty($email) && !empty($password)) {
        // Check duplicate user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Username or Email already exists.';
        } else {
            // Hash password securely
            $hashed_pwd = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$fullname, $username, $email, $hashed_pwd])) {
                $success = 'Registration successful! <a href="login.php">Click here to Login</a>';
            } else {
                $error = 'Failed to register account.';
            }
        }
    } else {
        $error = 'All fields are required.';
    }
}
?>

<h1>Register Account</h1>
<?php 
if ($error) echo "<div class='alert alert-danger'>$error</div>";
if ($success) echo "<div class='alert alert-success'>$success</div>";
?>

<form action="register.php" method="POST" style="margin-top: 1rem;">
    <div class="form-group">
        <label>Full Name:</label>
        <input type="text" name="fullname" required>
    </div>
    <div class="form-group">
        <label>Username:</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Email Address:</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Register</button>
</form>

<?php include 'includes/footer.php'; ?>