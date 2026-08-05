<?php
require_once "config.php";
require_once "auth_check.php";
$userId = $_SESSION["user_id"];
$message = "";
$error = "";
$stmt = $pdo->prepare(
    "SELECT username, email, created_at
     FROM users
     WHERE id = ?"
);

$stmt->execute([$userId]);

$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}
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
    }
    elseif ($email === "") {

        $error = "Email is required";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address";
    }
    elseif ($password !== "") {

        if (strlen($password) < 8) {

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
        }
    }
    if ($error === "") {

        $checkStmt = $pdo->prepare(
            "SELECT id
             FROM users
             WHERE (email = ? OR username = ?)
             AND id != ?"
        );

        $checkStmt->execute([
            $email,
            $username,
            $userId
        ]);

        if ($checkStmt->fetch()) {

            $error = "Username or email is already in use";
        }
    }

    if ($error === "") {

        if ($password !== "") {

            $hashed = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $updateStmt = $pdo->prepare(
                "UPDATE users
                 SET username = ?, email = ?, password = ?
                 WHERE id = ?"
            );

            $updateStmt->execute([
                $username,
                $email,
                $hashed,
                $userId
            ]);

        } else {

            $updateStmt = $pdo->prepare(
                "UPDATE users
                 SET username = ?, email = ?
                 WHERE id = ?"
            );

            $updateStmt->execute([
                $username,
                $email,
                $userId
            ]);
        }
        $_SESSION["username"] = $username;

        $user["username"] = $username;
        $user["email"] = $email;


        $message = "Profile updated successfully!";
    }
}


$activeId = null;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <meta charset="UTF-8">

    <title>Profile Settings - Lumio</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="app-layout">

        <?php include "sidebar.php"; ?>


        <div class="main-content">

            <div class="dashboard-header">

                <h1>User Profile 👤</h1>

                <p>
                    Manage your account settings and credentials.
                </p>

            </div>


            <?php if ($message): ?>

                <div class="success-msg">
                    <?= htmlspecialchars($message) ?>
                </div>

            <?php endif; ?>


            <?php if ($error): ?>

                <div class="error-msg">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>


            <div class="card-panel">

                <form method="POST" class="profile-form">

                    <div class="form-group">

                        <label>Username</label>

                        <input
                            type="text"
                            name="username"
                            value="<?= htmlspecialchars($user['username']) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Email Address</label>

                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            New Password
                            (leave blank to keep current)
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                        >

                    </div>


                    <div class="form-group">

                        <label>Account Created</label>

                        <input
                            type="text"
                            value="<?= htmlspecialchars($user['created_at']) ?>"
                            readonly
                            disabled
                            style="opacity: 0.7;"
                        >

                    </div>


                    <button
                        type="submit"
                        style="
                            background: #4f8cff;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 6px;
                            color: #fff;
                            font-weight: bold;
                            cursor: pointer;
                        "
                    >
                        Save Changes
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>