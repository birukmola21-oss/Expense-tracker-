<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle expense deletion
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $user_id]);
    $message = '<div class="alert alert-success">Expense entry deleted successfully.</div>';
}

// Handle adding new expense
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $amount = floatval($_POST['amount']);
    $category = trim($_POST['category']);
    $expense_date = $_POST['expense_date'];

    if (!empty($title) && $amount > 0 && !empty($category) && !empty($expense_date)) {
        $stmt = $pdo->prepare("INSERT INTO expenses (user_id, title, amount, category, expense_date) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $title, $amount, $category, $expense_date])) {
            $message = '<div class="alert alert-success">Expense recorded successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error saving expense.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Please enter valid expense details.</div>';
    }
}

// Fetch user's expenses
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY expense_date DESC");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll();

// Calculate Total
$total_amount = array_sum(array_column($expenses, 'amount'));
?>

<h1>Dashboard - Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
<?php echo $message; ?>

<div style="margin: 1.5rem 0; padding: 1rem; background: #e8f8f5; border-left: 5px solid #1abc9c;">
    <h2>Total Spending: $<?php echo number_format($total_amount, 2); ?></h2>
</div>

<h3>Add New Expense</h3>
<form action="dashboard.php" method="POST" style="margin-top: 1rem; margin-bottom: 2rem;">
    <div class="form-group">
        <label>Title / Description:</label>
        <input type="text" name="title" placeholder="e.g. Textbooks, Groceries" required>
    </div>
    <div class="form-group">
        <label>Amount ($):</label>
        <input type="number" step="0.01" name="amount" required>
    </div>
    <div class="form-group">
        <label>Category:</label>
        <select name="category" required>
            <option value="Food">Food & Dining</option>
            <option value="Utilities">Utilities & Bills</option>
            <option value="Education">Education</option>
            <option value="Entertainment">Entertainment</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group">
        <label>Date:</label>
        <input type="date" name="expense_date" required>
    </div>
    <button type="submit" class="btn">Add Expense</button>
</form>

<h3>Your Recorded Expenses</h3>
<?php if (count($expenses) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Category</th>
                <th>Amount ($)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['expense_date']); ?></td>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td>$<?php echo number_format($item['amount'], 2); ?></td>
                    <td><a href="dashboard.php?delete=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure?')" style="color: red; text-decoration: none;">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="margin-top: 1rem;">No expense records found yet. Add one above!</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>