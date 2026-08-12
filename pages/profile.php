<?php
require_once "config.php";
require_once "auth_check.php";
require_once "theme.php";

$userId = $_SESSION["user_id"];

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

$activeId = null;
$theme = getUserTheme($pdo, $userId);
$joinedDate = !empty($user['created_at']) ? date("F j, Y", strtotime($user['created_at'])) : "N/A";
$userInitial = strtoupper(substr($user['username'] ?? 'U', 0, 1));

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" class="<?= $theme === 'light' ? 'light-mode' : 'dark-mode' ?>">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile - Lumio</title>

    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/profile.css">

</head>

<body>

    <div class="app-layout">

        <?php include "sidebar.php"; ?>


        <div class="main-content">

            <div class="dashboard-header">

                <h1>User Profile 👤</h1>

                <p>
                    View your personal account details and profile information.
                </p>

            </div>


            <!-- Profile Hero Card -->
            <div class="profile-hero-card">
                <div class="profile-avatar-large">
                    <?= htmlspecialchars($userInitial) ?>
                </div>
                <div class="profile-hero-info">
                    <h2><?= htmlspecialchars($user['username']) ?></h2>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>


            <!-- Profile Cards Grid -->
            <div class="profile-grid">

                <div class="profile-card">
                    <div class="profile-card-icon">👤</div>
                    <div class="profile-card-details">
                        <span class="profile-card-label">Username</span>
                        <h3 class="profile-card-value"><?= htmlspecialchars($user['username']) ?></h3>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="profile-card-icon">📧</div>
                    <div class="profile-card-details">
                        <span class="profile-card-label">Email Address</span>
                        <h3 class="profile-card-value"><?= htmlspecialchars($user['email']) ?></h3>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="profile-card-icon">📅</div>
                    <div class="profile-card-details">
                        <span class="profile-card-label">Account Created At</span>
                        <h3 class="profile-card-value"><?= htmlspecialchars($joinedDate) ?></h3>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

</html>