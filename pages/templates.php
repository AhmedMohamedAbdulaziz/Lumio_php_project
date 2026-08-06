<?php
require_once "config.php";
require_once "auth_check.php";
$activeId = null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Templates Library - Lumio Module</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/modules.css">
</head>
<body>
    <div class="app-layout">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <div class="dashboard-header">
                <h1>Templates Library 📑</h1>
                <p>Browse pre-designed page layouts for meetings, notes, and docs.</p>
            </div>

            <div class="team-placeholder">
                <div class="module-icon">🎨</div>
                <h2>Templates Library Module</h2>
                <p>This module is prepared for Team Member 5 to implement template selection, custom template creation, and one-click duplication.</p>
                <span class="badge badge-warning">Pending Team Implementation</span>
            </div>
        </div>
    </div>
</body>
</html>
