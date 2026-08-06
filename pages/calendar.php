<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Calendar - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Events & Calendar 📅</h1>
                <p>Schedule meetings, track deadlines, and view upcoming events.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">📆</div>
                <h2>Events & Calendar Module</h2>
                <p>This module is prepared for Team Member 1 to implement interactive calendar grids, event scheduling, and reminders.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
