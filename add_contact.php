<?php
require_once 'includes/auth.php';
redirect_if_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $value = $_POST['value'];
    $notes = $_POST['notes'];
    $label = $_POST['label'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO contacts (user_id, name, value, notes, label) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $name, $value, $notes, $label);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Add New Contact</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Value: <input type="text" name="value"><br><br>
    Notes:<br>
    <textarea name="notes" rows="4" cols="40"></textarea><br><br>
    Label:
    <select name="label" required>
        <option value="lead">Lead</option>
        <option value="customer">Customer</option>
        <option value="associate">Associate</option>
    </select><br><br>
    <button type="submit">Add Contact</button>
</form>
