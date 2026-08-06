<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Team Members - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Team Members & Roles 👥</h1>
                <p>View organization roster, assign access permissions, and collaborate.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">🤝</div>
                <h2>Team Directory Module</h2>
                <p>This module is prepared for Team Member 2 to implement member invitations, role management, and activity statuses.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
