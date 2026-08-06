<?php
require_once "config.php";

$error = "";

$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "") {
        $error = "Username is required";

    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $error = "Username must be between 3 and 20 characters";

    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error = "Username can only contain letters, numbers, and underscores";

    

    } elseif ($email === "") {
        $error = "Email is required";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";

    
    } elseif ($password === "") {
        $error = "Password is required";

    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";

    } elseif (!preg_match("/[A-Z]/", $password)) {
        $error = "Password must contain at least one uppercase letter";

    } elseif (!preg_match("/[a-z]/", $password)) {
        $error = "Password must contain at least one lowercase letter";

    } elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password must contain at least one number";

    } elseif (!preg_match("/[\W_]/", $password)) {
        $error = "Password must contain at least one special character";

    } elseif (preg_match("/\s/", $password)) {
        $error = "Password cannot contain spaces";

    } else {

       

        $stmt = $pdo->prepare(
            "SELECT id FROM users WHERE email = ? OR username = ?"
        );

        $stmt->execute([$email, $username]);

        if ($stmt->fetch()) {

            $error = "Username or email is already in use";

        } else {

       
            $hashed = password_hash($password, PASSWORD_DEFAULT);

        
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $username,
                $email,
                $hashed
            ]);

            $_SESSION["user_id"] = $pdo->lastInsertId();
            $_SESSION["username"] = $username;

            
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Register - Lumio</title>

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>

    <div class="auth-box">

        <h1>Lumio ✨</h1>

        <h2>Create a new account</h2>

        <?php if ($error): ?>

            <p class="error-msg">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>


        <form method="POST">

            <input
                type="text"
                name="username"
                placeholder="Username"
                value="<?= htmlspecialchars($username) ?>"
            >

            <input
                type="email"name="email" placeholder="Email Address"value="<?= htmlspecialchars($email) ?>"
            >

            <input
                type="password"name="password"placeholder="Password"  >

            <button type="submit">
                Register
            </button>

        </form>

        <p>
            Already have an account?
            <a href="login.php">Log in</a>
        </p>

    </div>
</body>
</html>