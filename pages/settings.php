<?php

require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";

$userId = $_SESSION["user_id"];
$theme = getUserTheme($pdo, $userId);

$message = "";
$error = "";


/* =========================================
   Get User
========================================= */

$sql = "SELECT username, email
        FROM users
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);


/* =========================================
   Get Settings
========================================= */

$sql = "SELECT *
        FROM user_settings
        WHERE user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$settings = $stmt->fetch(PDO::FETCH_ASSOC);


/* =========================================
   Create Default Settings
========================================= */

if (!$settings) {

    $sql = "INSERT INTO user_settings
            (user_id, theme, email_notifications)
            VALUES (?, 'dark', 1)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $userId
    ]);

    $settings = [
        "theme" => "dark",
        "email_notifications" => 1
    ];
}


/* =========================================
   Handle Settings
========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    /* =====================================
       Update Account
    ===================================== */

    if ($action === "account") {

        $username = trim(
            $_POST["username"] ?? ""
        );

        $email = trim(
            $_POST["email"] ?? ""
        );


        if ($username === "" || $email === "") {

            $error =
                "Username and email are required.";

        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $error =
                "Please enter a valid email.";

        } else {

            $sql = "UPDATE users
                    SET username = ?,
                        email = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $username,
                $email,
                $userId
            ]);


            $user["username"] =
                $username;

            $user["email"] =
                $email;


            $message =
                "Account information updated successfully.";
        }
    }


    /* =====================================
       Change Password
    ===================================== */

    if ($action === "password") {

        $currentPassword =
            $_POST["current_password"] ?? "";

        $newPassword =
            $_POST["new_password"] ?? "";

        $confirmPassword =
            $_POST["confirm_password"] ?? "";


        if (
            $currentPassword === "" ||
            $newPassword === "" ||
            $confirmPassword === ""
        ) {

            $error =
                "Please fill all password fields.";

        } elseif (
            $newPassword !== $confirmPassword
        ) {

            $error =
                "New passwords do not match.";

        } elseif (
            strlen($newPassword) < 6
        ) {

            $error =
                "Password must be at least 6 characters.";

        } else {

            $sql = "SELECT password
                    FROM users
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $userId
            ]);

            $passwordHash =
                $stmt->fetchColumn();


            if (
                !password_verify(
                    $currentPassword,
                    $passwordHash
                )
            ) {

                $error =
                    "Current password is incorrect.";

            } else {

                $newPasswordHash =
                    password_hash(
                        $newPassword,
                        PASSWORD_DEFAULT
                    );


                $sql = "UPDATE users
                        SET password = ?
                        WHERE id = ?";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    $newPasswordHash,
                    $userId
                ]);


                $message =
                    "Password changed successfully.";
            }
        }
    }


    /* =====================================
       Appearance
    ===================================== */

    if ($action === "appearance") {

        $theme =
            $_POST["theme"] ?? "dark";


        if (
            $theme !== "dark" &&
            $theme !== "light"
        ) {

            $theme = "dark";
        }


        $sql = "UPDATE user_settings
                SET theme = ?
                WHERE user_id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $theme,
            $userId
        ]);


        $settings["theme"] =
            $theme;


        $message =
            "Appearance updated.";
    }


    /* =====================================
       Notifications
    ===================================== */

    if ($action === "notifications") {

        $emailNotifications =
            isset(
                $_POST["email_notifications"]
            )
                ? 1
                : 0;


        $sql = "UPDATE user_settings
                SET email_notifications = ?
                WHERE user_id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $emailNotifications,
            $userId
        ]);


        $settings["email_notifications"] =
            $emailNotifications;


        $message =
            "Notification settings updated.";
    }
}

?>

<!DOCTYPE html>

<html
    lang="en"
    class="<?= $theme === "light"
        ? "light-mode"
        : "dark-mode" ?>"
>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Settings - Lumio</title>

    <link
        rel="stylesheet"
        href="../css/base.css"
    >

    <link
        rel="stylesheet"
        href="../css/task.css"
    >

</head>


<body>

<div class="app-layout">


    <?php include "sidebar.php"; ?>


    <main class="main-content">


        <!-- Header -->

        <div class="settings-page-header">

            <div>

                <span class="settings-label">
                    ACCOUNT SETTINGS
                </span>

                <h1>Settings</h1>

                <p>
                    Manage your account and customize
                    your Lumio experience.
                </p>

            </div>

        </div>


        <!-- Messages -->

        <?php if ($message): ?>

            <div class="settings-message success">

                ✓
                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>


        <?php if ($error): ?>

            <div class="settings-message error">

                !
                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <div class="settings-grid">


            <!-- =================================
                 Account
            ================================== -->

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-icon">
                        👤
                    </div>

                    <div>

                        <h2>
                            Account
                        </h2>

                        <p>
                            Update your personal information.
                        </p>

                    </div>

                </div>


                <form method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="account"
                    >


                    <div class="settings-form-group">

                        <label>
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            value="<?= htmlspecialchars(
                                $user["username"]
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="settings-form-group">

                        <label>
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars(
                                $user["email"]
                            ) ?>"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="settings-btn"
                    >
                        Save Changes
                    </button>

                </form>

            </section>


            <!-- =================================
                 Security
            ================================== -->

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-icon">
                        🔐
                    </div>

                    <div>

                        <h2>
                            Security
                        </h2>

                        <p>
                            Keep your account secure.
                        </p>

                    </div>

                </div>


                <form method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="password"
                    >


                    <div class="settings-form-group">

                        <label>
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            required
                        >

                    </div>


                    <div class="settings-form-group">

                        <label>
                            New Password
                        </label>

                        <input
                            type="password"
                            name="new_password"
                            minlength="6"
                            required
                        >

                    </div>


                    <div class="settings-form-group">

                        <label>
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            minlength="6"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="settings-btn"
                    >
                        Update Password
                    </button>

                </form>

            </section>


            <!-- =================================
                 Appearance
            ================================== -->

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-icon">
                        🎨
                    </div>

                    <div>

                        <h2>
                            Appearance
                        </h2>

                        <p>
                            Choose how Lumio looks.
                        </p>

                    </div>

                </div>


                <form method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="appearance"
                    >


                    <div class="theme-options">


                        <label class="theme-option">

                            <input
                                type="radio"
                                name="theme"
                                value="dark"
                                <?= $settings["theme"] === "dark"
                                    ? "checked"
                                    : "" ?>
                            >

                            <div class="theme-preview dark-preview">

                                <div class="preview-bar"></div>

                                <div class="preview-content">

                                    <div></div>
                                    <div></div>
                                    <div></div>

                                </div>

                            </div>

                            <strong>
                                Dark
                            </strong>

                            <span>
                                Easy on the eyes
                            </span>

                        </label>


                        <label class="theme-option">

                            <input
                                type="radio"
                                name="theme"
                                value="light"
                                <?= $settings["theme"] === "light"
                                    ? "checked"
                                    : "" ?>
                            >

                            <div class="theme-preview light-preview">

                                <div class="preview-bar"></div>

                                <div class="preview-content">

                                    <div></div>
                                    <div></div>
                                    <div></div>

                                </div>

                            </div>

                            <strong>
                                Light
                            </strong>

                            <span>
                                Clean and bright
                            </span>

                        </label>


                    </div>


                    <button
                        type="submit"
                        class="settings-btn"
                    >
                        Apply Theme
                    </button>

                </form>

            </section>


            <!-- =================================
                 Notifications
            ================================== -->

            <section class="settings-card">

                <div class="settings-card-header">

                    <div class="settings-icon">
                        🔔
                    </div>

                    <div>

                        <h2>
                            Notifications
                        </h2>

                        <p>
                            Control your notification preferences.
                        </p>

                    </div>

                </div>


                <form method="POST">

                    <input
                        type="hidden"
                        name="action"
                        value="notifications"
                    >


                    <label class="toggle-setting">

                        <div>

                            <strong>
                                Email Notifications
                            </strong>

                            <p>
                                Receive important updates
                                about your workspace.
                            </p>

                        </div>


                        <input
                            type="checkbox"
                            name="email_notifications"
                            <?= $settings["email_notifications"]
                                ? "checked"
                                : "" ?>
                        >

                    </label>


                    <button
                        type="submit"
                        class="settings-btn"
                    >
                        Save Preferences
                    </button>

                </form>

            </section>


        </div>


    </main>

</div>

</body>

</html>