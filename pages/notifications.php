<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Notifications - Lumio Module</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Notifications & Alerts 🔔</h1>
                <p>Stay updated on mentions, page changes, and team activities.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">📬</div>
                <h2>Notifications Center Module</h2>
                <p>This module is prepared for Team Member 4 to implement real-time alerts, mark as read features, and activity logs.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
