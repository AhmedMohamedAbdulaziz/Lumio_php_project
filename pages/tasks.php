<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Tasks - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Task Management Board ✅</h1>
                <p>Organize, track, and assign team tasks and kanban workflows.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">📋</div>
                <h2>Task Board Module</h2>
                <p>This module is prepared for Team Member 1 to implement task creation, drag-and-drop boards, and status filters.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
