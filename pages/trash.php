<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Trash - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Trash & Archive 🗑</h1>
                <p>Recover deleted pages or permanently remove them from your workspace.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">♻️</div>
                <h2>Trash & Archive Module</h2>
                <p>This module is prepared for Team Member 5 to implement soft-deletion, page recovery, permanent removal, and auto-cleanup policies.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
