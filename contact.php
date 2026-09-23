<?php
require_once 'config/db.php';
include 'includes/header.php';

$message_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $msg = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($msg)) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $msg])) {
            $message_status = '<div class="alert alert-success">Thank you! Your message has been received.</div>';
        } else {
            $message_status = '<div class="alert alert-danger">Error submitting message.</div>';
        }
    } else {
        $message_status = '<div class="alert alert-danger">Please fill in all fields.</div>';
    }
}
?>

<h1>Contact Us</h1>
<?php echo $message_status; ?>

<form action="contact.php" method="POST" style="margin-top: 1.5rem;">
    <div class="form-group">
        <label>Your Name:</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-group">
        <label>Email Address:</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Message:</label>
        <textarea name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn">Send Message</button>
</form>

<?php
include 'includes/footer.php';
?>