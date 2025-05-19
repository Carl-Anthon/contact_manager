<?php
require_once 'includes/auth.php';
redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM contacts WHERE user_id = $user_id");

echo "<h2>Your Contacts</h2>";
echo "<a href='add_contact.php'>Add Contact</a> | <a href='logout.php'>Logout</a><br><br>";

while ($row = $result->fetch_assoc()) {
    echo "<b>{$row['name']}</b> - {$row['label']}<br>";
    echo "Value: {$row['value']}<br>";
    echo "Notes: {$row['notes']}<br><hr>";
}
?>
