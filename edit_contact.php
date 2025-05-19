<?php
require_once 'includes/db.php';

if (!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = $_GET['token'];

$stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Token expired or invalid.");
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_pass, $user_id);
    $stmt->execute();

    $conn->query("DELETE FROM password_resets WHERE user_id = $user_id");

    echo "Password updated successfully. <a href='login.php'>Login</a>";
    exit;
}
?>

<h2>Reset Your Password</h2>
<form method="POST">
    New Password: <input type="password" name="new_password" required><br><br>
    <button type="submit">Update Password</button>
</form>
