<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Contact Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Welcome to Contact Manager</h2>
    <p>This is a simple CRM system where you can manage your contacts and categorize them as Leads, Customers, or Associates.</p>

    <div style="margin-top: 30px;">
        <a href="login.php"><button>Login</button></a>
        <a href="register.php"><button>Register</button></a>
        <a href="forgot_password.php"><button>Forgot Password</button></a>
    </div>

</body>
</html>
